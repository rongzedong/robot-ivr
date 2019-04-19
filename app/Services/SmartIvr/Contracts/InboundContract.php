<?php
/**
 * Created by PhpStorm.
 * User: telrobot
 * Date: 2019/1/15
 * Time: 14:46
 */

namespace App\Services\SmartIvr\Contracts;

/**
 * 呼入接口
 * Interface InboundContract
 * @package App\Services\SmartIvr\Contracts
 */
interface InboundContract
{
    /**
     * @param NotifyContract $notify
     * @return InboundTaskContract
     */
    public function getTask(NotifyContract $notify);

    public function hangup(NotifyContract $notify);
}