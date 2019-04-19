<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/11/23
 * Time: 23:58
 */

namespace App\Services\SmartIvr\Handles;

use App\Events\Inbound\BuildAnswerDetailEvent;

/**
 * 呼入
 * Class Outbound
 * @package App\Services\SmartIvr\Handles
 */
class Inbound extends BaseHandle
{
    public function fireBuildAnswerDetailEvent($notify, $model)
    {
        BuildAnswerDetailEvent::dispatch($notify->getReceiveData()->toArray(), $this->handleEntity, $model);
    }

}