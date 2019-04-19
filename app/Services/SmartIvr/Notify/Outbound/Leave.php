<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/22
 * Time: 1:10
 */

namespace App\Services\SmartIvr\Notify\Outbound;

use App\Services\SmartIvr\Events\Notify\Outbound\LeavedEvent;
use App\Services\SmartIvr\Events\Notify\Outbound\LeavingEvent;
use App\Services\SmartIvr\Payload\Noop;

/**
 * 离开流程
 * 比如挂机(hangup)，deflect，transfer
 * Class Leave
 * @package App\Services\SmartIvr\Notify
 */
class Leave extends Notify
{
    public function handle()
    {
        LeavingEvent::dispatch($this);

        $this->payload = new Noop();

        LeavedEvent::dispatch($this);

        //清理通话过程数据
        $this->resolveHandle()->clear();

        return $this->getPayload();
    }
}