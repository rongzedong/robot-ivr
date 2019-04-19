<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/27
 * Time: 20:29
 */

namespace App\Services\SmartIvr\Notify\Outbound;

use App\Services\SmartIvr\Events\Notify\Outbound\PlaybackResultedEvent;
use App\Services\SmartIvr\Events\Notify\Outbound\PlaybackResultingEvent;
use App\Services\SmartIvr\Handles\Outbound;
use App\Services\SmartIvr\Payload\Noop;

/**
 * 声音播放完成并且等待时间超过
 * Class PlaybackResult
 * @package App\Services\SmartIvr\Notify
 */
class PlaybackResult extends Notify
{

    /**
     * @var Outbound $handle
     */
    protected $handle;

    public function handle()
    {

        if ($this->isHangup()) {
            //已挂断
            return new Noop();
        }

        //分发事件
        PlaybackResultingEvent::dispatch($this);

        if ($this->checkAsrState()) {
            //用户当前刚好正在说话
            return new Noop();
        }

        //进入下个流程
        $handle = $this->resolveHandle();

        $this->payload = $handle->nextFlow($this, $this->getEnquire());

        PlaybackResultedEvent::dispatch($this);
        return $this->getPayload();

    }

    /**
     * 检查用户当前是否正在说话
     * @return bool
     */
    public function checkAsrState()
    {
        return $this->receiveData->asrstate;
    }


}