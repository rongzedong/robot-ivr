<?php

namespace App\Services\SmartIvr\Notify\Inbound;


use App\Services\SmartIvr\Contracts\HandleContract;
use App\Services\SmartIvr\Contracts\InboundContract;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Exceptions\SmartIvrBadParamException;
use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use App\Services\SmartIvr\Handles\Inbound;

/**
 * 呼入通知
 * Class Notify
 * @package App\Services\SmartIvr\Notify\Inbound
 */
abstract class Notify extends NotifyContract
{
    protected function init()
    {
        $this->handleClass = Inbound::class;
    }

    /**
     * 被叫
     * @return mixed|string
     */
    public function getCalleeId()
    {
        return $this->receiveData->calleeid;
    }

    /**
     * 主叫号码
     * @return mixed
     */
    public function getCallerId()
    {
        return $this->receiveData->origcallerid;
    }

    /**
     * 获取 话术处理类
     * @return HandleEntity
     */
    protected function getHandleEntity()
    {

        $inbound = app(InboundContract::class);

        if (!$inbound instanceof InboundContract) {
            throw new SmartIvrBadParamException('呼入功能与任务模块对接失败');
        }

        $task = $inbound->getTask($this);

        if (empty($task)) {
            throw new SmartIvrBadParamException('有号码 ' . $this->getCalleeId() . ' 呼入，但没有设置该号码的呼入任务');

        }

        $entity = new HandleEntity();
        $entity->setCallId($this->getCallId())
            ->setOrderNo($this->getCallerId())
            ->setLineNo($this->getCalleeId())
            ->setTaskId($task->getTaskId())
            ->setScriptGroup($task->getScriptGroupId());

        return $entity;
    }
}