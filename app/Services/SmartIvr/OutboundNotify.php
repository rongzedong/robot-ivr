<?php
/**
 * Created by PhpStorm.
 * User: yangx
 * Date: 2018/12/2
 * Time: 19:42
 */

namespace App\Services\SmartIvr;


use Throwable;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Exceptions\SmartIvrBadParamException;

/**
 * 外呼入口
 * Class Outbound
 * @package App\Services\SmartIvr
 */
class OutboundNotify extends SmartIvr
{

    /**
     * 解析通知
     * @return string
     * @throws SmartIvrBadParamException
     * @throws Throwable
     */
    public function parseNotify(): string
    {
        try {
            $notifyClassStr = '\App\Services\SmartIvr\Notify\Outbound\\' . studly_case($this->receiveData->notify);
            /**
             * @var NotifyContract $notify
             */
            $notify = new $notifyClassStr($this->receiveData);
            $payload = $notify->handle();
            return (string)$payload;
        } catch (Throwable $e) {
            throw new SmartIvrBadParamException($this->receiveData->notify . ' 异常', 200, $e);
        }


    }
}