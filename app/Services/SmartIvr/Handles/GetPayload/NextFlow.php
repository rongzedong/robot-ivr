<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/2/25
 * Time: 16:43
 */

namespace App\Services\SmartIvr\Handles\GetPayload;

use App\Services\SmartIvr\Contracts\HandleContract;
use App\Services\SmartIvr\Contracts\HandleGetPayloadContract;
use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Contracts\RepositoryContract;
use App\Services\SmartIvr\Handles\BaseHandle;
use App\Services\SmartIvr\Payload\PlayAfterHangup;
use App\Services\SmartIvr\Payload\Playback;
use Illuminate\Database\Eloquent\Collection;

/**
 * 一轮对话完成后，进行下一轮回答
 * Class NextFlow
 * @package App\Services\SmartIvr\Handles\GetPayload
 */
class NextFlow implements HandleGetPayloadContract
{
    /**
     * @var BaseHandle $handle
     */
    protected $handle;

    /**
     * @var RepositoryContract $repository 话术资源
     */
    protected $repository;

    /**
     * @var bool 是否用户无回应时主动发起询问
     */
    protected $enquire = true;

    protected $callId;

    /**
     * ParseAsr constructor.
     * @param HandleContract $handle
     */
    public function __construct(HandleContract $handle)
    {
        $this->handle = $handle;
        $this->init();
    }

    protected function init()
    {
        $this->callId = $this->handle->getHandleEntity()->callId;
        $this->repository = $this->handle->getRepository();
    }

    public function setEnquire($value)
    {
        $this->handle->setEnquire($value);
        return $this;
    }


    public function getPayload(NotifyContract $notify = null)
    {
        $payload = null;
        $model = null;
        //判断用户有没有回应
        if ($this->handle->isSpeak()) {
            //重置回应标记
            $this->handle->userSpeak(false);

            $model = $this->handle->getNormal();
        } else {
            //没有回应时
            if ($this->handle->isEnquire()) {
                //主动询问
                $model = $this->getSpecialNoAnswer(true);
                if ($model instanceof ModelContract) {
                    $payload = $this->parseNoAnswer($model);
                }
            } else {
                //没有无回应的话术时，下个流程
                $model = $this->handle->getNormal();
            }
        }

        if (is_null($payload)) {
            if (is_null($model)) {
                //随机获取一个结束语
                $payload = $this->handle->hangup($data = $this->handle->firstSpecialHangup());
            } else {

                $payload = new Playback($this->handle->getHandleEntity(), $model);
            }
        }

        //分发生成通话明细事件
        if ($notify) {
            $this->handle->fireBuildAnswerDetailEvent($notify, $model);
        }
        return $this->handle->finalTreatmentPayload($payload);
    }

    /**
     * 获取用户一直无回复时的应答回复
     *
     * @param true $is_record 是否同时记录使用次数
     * @param false $reset 是重置使用次数
     * @return ModelContract|null
     */
    protected function getSpecialNoAnswer($is_record = true, $reset = false)
    {
        $record_key = $this->callId . '_no_answer';
        //优先场景话术
        /**@var  Collection $models */
        $models = $this->repository->getSpecialNoAnswer($this->handle->scene())->filter->filterUsedCallRecord($record_key);
        if ($models->isEmpty() && $this->handle->scene()) {
            //全局话术
            $models = $this->repository->getSpecialNoAnswer()->filter->filterUsedCallRecord($record_key);
        }

        if ($models->isEmpty()) {
            return null;
        }

        return $this->handle->useRecord($models->first(), $is_record, $reset, $record_key);
    }

    /**
     * 处理无回应话术
     * @param ModelContract $model
     * @return PlayAfterHangup|Playback
     */
    protected function parseNoAnswer(ModelContract $model)
    {
        if ($model->isActionHangup()) {
            //挂断
            $payload = new PlayAfterHangup($this->handle->getHandleEntity(), $model);
        } else {
            $payload = new Playback($this->handle->getHandleEntity(), $model);
        }
        return $payload;
    }
}