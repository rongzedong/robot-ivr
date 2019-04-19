<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/10
 * Time: 15:13
 */

namespace App\Services\SmartIvr\Payload;

use App\Services\SmartIvr\Contracts\PayloadContract;

/**
 * 转移（SIP REFER ）
 * Class Deflect
 * @package App\Services\SmartIvr\Payload
 */
class Deflect extends PayloadContract
{
    protected function init()
    {
        $this->action('deflect')
            ->number($this->model->getBridgeNumber());
    }

    public function number($value)
    {
        return $this->params('number', $value);
    }
}