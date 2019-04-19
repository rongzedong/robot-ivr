<?php
/**
 * Created by PhpStorm.
 * User: yangx
 * Date: 2018/12/10
 * Time: 10:58
 */

namespace App\Services\SmartIvr\Notify\Outbound;


use App\Services\SmartIvr\Contracts\HandleContract;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Exceptions\SmartIvrBadParamException;
use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use App\Services\SmartIvr\Handles\Outbound;

abstract class Notify extends NotifyContract
{
    protected function init()
    {
        $this->handleClass = Outbound::class;
    }

    /**
     * 获取 话术处理类
     * @return HandleEntity
     */
    protected function getHandleEntity()
    {
        $entity = new HandleEntity();
        $entity->setTaskId($this->getTaskId())
            ->setScriptGroup($this->getTaskTemplateGroup())
            ->setCallId($this->getCallRecordKey())
            ->setOrderNo($this->getCalleeId())
            ->setLineNo($this->getCallerId());

        return $entity;
    }
}