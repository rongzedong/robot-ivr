<?php
/**
 * Created by PhpStorm.
 * User: yangx
 * Date: 2018/12/11
 * Time: 13:49
 */

namespace App\Services\SmartIvr\Notify\Inbound;

use App\Services\SmartIvr\Events\Notify\Inbound\BridgedEvent;
use App\Services\SmartIvr\Handles\Helper\BridgeStatus;
use App\Services\SmartIvr\Payload\Hangup;

/**
 * 转接结束，转接失败，或者转接成功通话结束才收到这个通知
 * Class BridgeResult
 * @package App\Services\SmartIvr\Notify\Outbound
 */
class BridgeResult extends Notify
{
    public function handle()
    {
        $handle = $this->resolveHandle();
        if ($this->getErrorCode() == 0) {
            //转接成功,通话结束后
            $handle->setBridgeStatus(BridgeStatus::SUCCESS);
            BridgedEvent::dispatch($this);
            $payload = new Hangup();
            return $payload->usermsg('bridge_success');
        }
        //转接失败，继续AI通话
        $handle->setBridgeStatus(BridgeStatus::FAILED);

        return $handle->bridgeFailedFlow()->getPayload($this);
    }
}