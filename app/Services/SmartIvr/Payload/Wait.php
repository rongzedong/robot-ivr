<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/27
 * Time: 23:03
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\PayloadContract;

/**
 * 等待时间 默认等1秒
 * Class Wait
 * @package App\Services\SmartIvr\Payload
 */
class Wait extends PayloadContract
{

    protected function init()
    {
        $this->action('wait')->params('timeout', 1000);
    }

    /**
     * @param int $value 单位毫秒
     * @return Wait
     */
    public function timeout($value)
    {
        return $this->params('timeout', $value);
    }


}