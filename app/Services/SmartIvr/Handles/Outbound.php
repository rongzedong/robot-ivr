<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/11/23
 * Time: 23:58
 */

namespace App\Services\SmartIvr\Handles;

use App\Events\AutoDialer\BuildAnswerDetailEvent;

/**
 * 外呼
 * Class Outbound
 * @package App\Services\SmartIvr\Handles
 */
class Outbound extends BaseHandle
{
    /**
     * 分发生成通话明细事件
     * @param $notify
     * @param $model
     */
    public function fireBuildAnswerDetailEvent($notify, $model)
    {
        BuildAnswerDetailEvent::dispatch($notify->getReceiveData()->toArray(), $this->handleEntity, $model);
    }
}