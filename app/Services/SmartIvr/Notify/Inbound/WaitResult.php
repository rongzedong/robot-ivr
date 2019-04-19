<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/12/24
 * Time: 17:11
 */

namespace App\Services\SmartIvr\Notify\Inbound;


use App\Services\SmartIvr\Payload\Noop;

/**
 * 等待超时
 * Class WaitResult
 * @package App\Services\SmartIvr\Notify\Outbound
 */
class WaitResult extends Notify
{
    public function handle()
    {
        return new Noop();
    }
}