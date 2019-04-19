<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/2/25
 * Time: 15:21
 */

namespace App\Services\SmartIvr\Handles;

use App\Models\AsrConfig;
use App\Repositories\AutoDialer\OutboundRepository;
use App\Repositories\Outbound\GroupRepository;
use App\Services\SmartIvr\Contracts\HandleContract;
use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Contracts\PayloadContract;
use App\Services\SmartIvr\Handles\GetPayload\BridgeFailedFlow;
use App\Services\SmartIvr\Handles\GetPayload\NextFlow;
use App\Services\SmartIvr\Handles\GetPayload\ParseAsr;
use App\Services\SmartIvr\Handles\Helper\BridgeStatus;
use App\Services\SmartIvr\Handles\Helper\Intervene;
use App\Services\SmartIvr\Handles\Helper\Reply;
use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use App\Services\SmartIvr\Handles\Helper\Records;
use App\Services\SmartIvr\Handles\Helper\Replies;
use App\Services\SmartIvr\Payload\Bridge;
use App\Services\SmartIvr\Payload\Hangup;
use App\Services\SmartIvr\Payload\PlayAfterHangup;
use App\Services\SmartIvr\Payload\Playback;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BaseHandle
 * @package App\Services\SmartIvr\Handles
 */
abstract class BaseHandle implements HandleContract
{

    protected $handleEntity;

    protected $question;

    /**
     * @var string $scriptGroup 话术组
     */
    protected $scriptGroup;

    /**
     * @var string 通话ID，做为缓存唯一键
     */
    protected $callId;

    /**
     * @var string $scene 提示上文
     */
    protected $scene = '';

    /**
     * 询问词
     * @var Collection $wordClassQuery
     */
    protected $wordClassQuery;

    /**
     * 否定
     * @var Collection $wordClassNo
     */
    protected $wordClassNo;

    /**
     * 肯定
     * @var Collection $wordClassYes
     */
    protected $wordClassYes;

    /**
     * 特殊词性
     * @var Collection $action_rebroadcast
     */
    protected $action_rebroadcast;

    /**
     * @var Collection $hangups
     */
    protected $hangups;

    /**
     * @var bool $actionNext
     */
    protected $actionNext = false;
    /**
     * @var Collection $hangups
     */
    protected $bridge;

    /**
     * @var OutboundRepository $repository 关键词资源
     */
    protected $repository;

    /**
     * @var ModelContract $currentModel 当前匹配到的回复标记
     */
    protected $currentModel;

    /**
     * @var Collection 匹配到的话术集
     */
    protected $collect;

    /**
     * @var bool 是否用户无回应时主动发起询问
     */
    protected $enquire = true;

    /**
     * Handle constructor.
     * @param HandleEntity $entity
     */
    public function __construct(HandleEntity $entity)
    {
        $this->handleEntity = $entity;

        $this->scriptGroup = $entity->scriptGroup;

        $this->callId = $entity->callId;

        $this->repository = OutboundRepository::instance()->setGroupId($this->scriptGroup);
    }

    /**
     * 获取待处理的实体对象
     * @return HandleEntity
     */
    public function getHandleEntity()
    {
        return $this->handleEntity;
    }

    /**
     * 获取话术资源对象
     * @return OutboundRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * 设置/获取通话场景
     * @param string $context
     * @return Outbound|string
     */
    public function scene($context = null)
    {
        if (is_null($context)) {
            return $this->scene;
        }

        $this->scene = $context;
        return $this;
    }

    /**
     * 话术使用记录
     *
     * @param ModelContract $model
     * @param bool $isRecord 是否需要记录使用次数
     * @param false $reset 是重置使用次数
     * @param string $recordKey 缓存的键
     * @return ModelContract
     */
    public function useRecord(ModelContract $model, $isRecord = true, $reset = false, $recordKey = null)
    {
        $key = $recordKey ?? $this->callId;

        $this->currentModel = $model;

        if ($isRecord) {
            $model->useCallRecord($key, $reset);
        }

        return $this->currentModel;
    }

    /**
     * 获取常规话术
     *
     * @param true $isRecord 是否同时记录使用次数
     * @param false $reset 是重置使用次数
     * @return ModelContract|null
     */
    public function getNormal($isRecord = true, $reset = false)
    {
        $record_key = $this->callId . '_normal';
        /**
         * @var Collection $models
         */
        $models = $this->repository->getNormal()->filter->filterUsedCallRecord($record_key);

        if ($models->isEmpty()) {
            return null;
        }
        $model = $models->first();

        return $this->useRecord($model, $isRecord, $reset, $record_key);
    }


    public function hasSpecialInvalid()
    {
        return (bool)$this->getSpecialInvalid(false);
    }

    /**
     * 获取无法识别有效声音时
     *
     * @param false $is_record 是否同时记录使用次数
     * @param false $reset 是重置使用次数
     * @return ModelContract|null
     */
    protected function getSpecialInvalid($is_record = true, $reset = false)
    {
        $record_key = $this->callId . '_invalid';

        /**@var  Collection $models */
        $models = $this->repository->getSpecialInvalid($this->scene)->filter->filterUsedCallRecord($record_key);
        if ($models->isEmpty() && $this->scene) {
            $models = $this->repository->getSpecialInvalid()->filter->filterUsedCallRecord($record_key);
        }

        if ($models->isEmpty()) {
            return null;
        }

        return $this->useRecord($models->random(), $is_record, $reset, $record_key);
    }

    /**
     * 无效噪音的处理话术
     * @param bool $is_record
     * @param bool $reset
     * @return Playback|Hangup|PlayAfterHangup
     */
    public function getPayloadInvalid($is_record = true, $reset = false)
    {
        $data = $this->getSpecialInvalid($is_record, $reset);
        if ($data) {
            return new Playback($this->handleEntity, $data);
        } else {
            //噪音挂断
            return $this->hangup($this->firstSpecialHangup());
        }
    }


    /**
     * 获取挂断话术-全局结束语第一条
     *
     * @return ModelContract|null
     */
    public function firstSpecialHangup()
    {
        //先上下文中查找，找不到全局随机获取一个结束语
        $models = $this->repository->getSpecialHangup();
        if ($models->isEmpty()) {
            return null;
        }

        $model = $models->first();

        if ($model instanceof ModelContract) {
            $this->currentModel = $model;
        }

        return $model;
    }

    /**
     * 执行挂断操作
     *
     * @param ModelContract $model
     * @return Hangup|PlayAfterHangup
     */
    public function hangup($model = null)
    {
        //清空本次通话已使用记录的缓存
        $this->clear();

        if ($model instanceof ModelContract) {
            return new PlayAfterHangup($this->handleEntity, $model);
        }
        return new Hangup();
    }

    /**
     * 清空本次通话已使用记录的缓存
     */
    public function clear()
    {
        Replies::instance()->setCacheKey($this->callId)->forget();
        Records::instance()->setCacheKey($this->callId)->forget();
        BridgeStatus::instance()->setCacheKey($this->callId)->forget();
        $this->intervene()->forget();
    }

    /**
     * 判断转接状态
     * @param $status
     * @return bool|mixed
     */
    public function bridgeStatusIs($status)
    {
        return BridgeStatus::instance()->setCacheKey($this->callId)->is($status);
    }

    /**
     * 设置转接状态
     * @param $status
     * @return $this
     */
    public function setBridgeStatus($status)
    {
        BridgeStatus::instance()->setCacheKey($this->callId)->set($status);
        return $this;
    }

    /**
     * 获取转接状态
     * @return int
     */
    public function getBridgeStatus()
    {
        return BridgeStatus::instance()->setCacheKey($this->callId)->get();
    }

    /**
     * 获取当前可使用的话术
     * @param null $model
     * @return ModelContract
     */
    public function currentVerbalTrick($model = null)
    {
        if (empty($model)) {
            return $this->currentModel;
        }
        $this->currentModel = $model;
    }

    /**
     * 分析回复
     * @param string $message 用户询问
     * @return ParseAsr
     */
    public function parse($message)
    {
        return (new ParseAsr($this))->message($message);
    }

    public function userSpeak($is = true)
    {
        Reply::instance()->setCacheKey($this->callId)->setReply($is);
    }

    public function isSpeak()
    {
        return Reply::instance()->setCacheKey($this->callId)->hasReply();
    }

    /**
     * 是否主动询问
     * @param $value
     * @return $this
     */
    public function setEnquire($value)
    {
        $this->enquire = $value;
        return $this;
    }

    /**
     * 是否主动询问
     * @return bool
     */
    public function isEnquire()
    {
        $models = $this->repository->getSpecialNoAnswer($this->scene);
        if ($models->isEmpty() && $this->scene) {
            //全局话术
            $models = $this->repository->getSpecialNoAnswer();
        }

        if ($models->isEmpty()) {
            //没有设置过主动询问话术
            return false;
        }
        //开启主动询问 且 还有主流话术未播
        return $this->enquire && $this->getNormal(false);
    }

    /**
     * 进入下一流程
     * @param NotifyContract $notify
     * @param bool $enquire 是否主动询问
     * @return Hangup|PlayAfterHangup|Playback
     */
    public function nextFlow(NotifyContract $notify = null, $enquire = null)
    {
        $nextFlow = new NextFlow($this);
        if (!is_null($enquire)) {
            $nextFlow->setEnquire($enquire);
        }
        return $nextFlow->getPayload($notify);
    }

    /**
     * 分发生成通话明细事件
     * @param NotifyContract $notify
     * @param $model
     */
    public abstract function fireBuildAnswerDetailEvent($notify, $model);

    /**
     * 转接后失败的流程处理
     * @return BridgeFailedFlow
     */
    public function bridgeFailedFlow()
    {
        return new BridgeFailedFlow($this);
    }

    /**
     * @return null|string
     */
    public function getAsrConfigFilename()
    {
        $group = GroupRepository::instance()->find($this->scriptGroup);
        if ($group) {
            $asrConfig = new AsrConfig();
            $asrConfig->user_id = $group->user_id;
            return $asrConfig->getRealFilename();
        }
        return null;
    }

    /**
     * @return Intervene
     */
    public function intervene()
    {
        return Intervene::instance()->setCacheKey($this->callId);
    }


    protected function checkIntervene(PayloadContract $payload)
    {
        $intervene = $this->intervene();
        //判断是否需要强制介入
        $bridgeNumber = $intervene->getBridgeNumber();
        if ($bridgeNumber) {
            $payload = new Bridge($this->handleEntity, $payload->getModel(), $bridgeNumber);
        } else {
            $hangup = $intervene->isHangup();
            if ($hangup) {
                $payload = $this->hangup($this->firstSpecialHangup());
            }
        }
        return $payload;
    }

    /**
     * 最后处理 payload
     * @param $payload
     * @return mixed
     */
    public function finalTreatmentPayload(PayloadContract $payload)
    {
        //是否需要强制介入
        $payload = $this->checkIntervene($payload);
        if ($payload instanceof Bridge) {
            $this->setBridgeStatus(BridgeStatus::PENDING);
        }
        return $payload;
    }
}