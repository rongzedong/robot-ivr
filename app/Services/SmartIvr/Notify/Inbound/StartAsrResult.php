<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/5/11
 * Time: 2:14
 */

namespace App\Services\SmartIvr\Notify\Inbound;

use App\Services\SmartIvr\Events\Notify\Inbound\StartAsrResultedEvent;
use App\Services\SmartIvr\Events\Notify\Inbound\StartAsrResultingEvent;
use App\Services\SmartIvr\Payload\Noop;

/**
 * 后台ASR启动结果
 * Class StartAsrResult
 * @package App\Services\SmartIvr\Notify
 */
class StartAsrResult extends Notify
{


    public function handle()
    {

        StartAsrResultingEvent::dispatch($this);
        if ($this->isHangup()) {
            //已挂断
            $this->payload = new Noop();
        } else {
            $handle = $this->resolveHandle();
            //标记用户已说话
            $handle->userSpeak(true);

            $this->payload = $handle->nextFlow($this, $this->getEnquire());
        }

        StartAsrResultedEvent::dispatch($this);

        return $this->getPayload();

    }


}