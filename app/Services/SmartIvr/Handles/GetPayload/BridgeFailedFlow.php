<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/2/25
 * Time: 18:55
 */

namespace App\Services\SmartIvr\Handles\GetPayload;


use App\Services\SmartIvr\Contracts\HandleContract;
use App\Services\SmartIvr\Contracts\HandleGetPayloadContract;
use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Contracts\RepositoryContract;
use App\Services\SmartIvr\Exceptions\PayloadException;
use App\Services\SmartIvr\Payload\Bridge;
use Illuminate\Database\Eloquent\Collection;

/**
 * 转接后的处理
 * Class BridgeFlow
 * @package App\Services\SmartIvr\Handles\GetPayload
 */
class BridgeFailedFlow implements HandleGetPayloadContract
{
    /**
     * @var HandleContract $handle
     */
    protected $handle;

    /**
     * @var RepositoryContract $repository 话术资源
     */
    protected $repository;


    protected $callId;

    /**
     * @var Collection $collect
     */
    protected $collect;

    protected $hasData;

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

    protected function match()
    {
        //搜索场景应答话术
        /**
         * @var Collection $models
         */
        $models = $this->repository->getSpecialBridgeFailed($this->handle->scene())->filter->filterUsedCallRecord($this->callId);
        if ($models->isEmpty() && $this->handle->scene()) {
            //全局搜索对应的话术
            $models = $this->repository->getSpecialBridgeFailed()->filter->filterUsedCallRecord($this->callId);
        }

        $this->collect = $models;

        $this->hasData = $models->isNotEmpty();
    }


    public function getPayload(NotifyContract $notify = null)
    {
        $this->match();

        try {
            foreach ($this->collect as $model) {
                $payload = null;
                if ($model instanceof ModelContract && $model->isWordClassOfSpecial()) {
                    $this->handle->currentVerbalTrick($model);
                    if ($model->isActionHangup()) {
                        //挂断
                        $payload = $this->handle->hangup($model);
                    } elseif ($model->isActionBridge()) {
                        $payload = new Bridge($this->handle->getHandleEntity(), $model);
                    }
                }

                if ($payload) {
                    throw new PayloadException($payload);
                }
            };
        } catch (PayloadException $e) {
            $payload = $e->getPayload();
            //添加通话使用记录缓存
            if ($model = $payload->getModel()) {
                $this->handle->useRecord($model);
            }
            return $this->handle->finalTreatmentPayload($payload);
        }
    }
}