<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/22
 * Time: 1:09
 */

namespace App\Services\SmartIvr\Notify\Inbound;

use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Events\Notify\Inbound\EnteredEvent;
use App\Services\SmartIvr\Events\Notify\Inbound\EnteringEvent;
use App\Services\SmartIvr\Payload\AsrPlayback;

/**
 * 进入流程
 * 比如来电应答后，外呼接通后进入流程
 * Class Enter
 * @package App\Services\SmartIvr\Notify
 */
class Enter extends Notify
{

    /**
     * 入口
     * @return \App\Services\SmartIvr\Contracts\PayloadContract
     */
    public function handle()
    {

        EnteringEvent::dispatch($this);

        $handle = $this->resolveHandle();
        $label = $handle->getNormal(true, true);
        if ($label instanceof ModelContract) {
            $this->payload = new AsrPlayback($handle->getHandleEntity(), $label);
            //获取个人asr 接口配置
            if ($filename = $handle->getAsrConfigFilename()) {
                $this->payload->asrConfigureFilename($filename);
            }
            EnteredEvent::dispatch($this);

            return $this->getPayload();
        }
        //没有设置话术的情况,直接挂断
        $this->payload = $handle->hangup($handle->firstSpecialHangup());
        EnteredEvent::dispatch($this);

        return $this->getPayload();

    }


}