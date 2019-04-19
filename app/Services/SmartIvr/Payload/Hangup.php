<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 1:57
 */

namespace App\Services\SmartIvr\Payload;

use App\Services\SmartIvr\Contracts\PayloadContract;

/**
 * Class Hangup
 * 挂机
 * @package App\Services\SmartIvr\Payload
 */
class Hangup extends PayloadContract
{

    protected function init()
    {
        $this->action('hangup')->cause(0);
    }

    /**
     * 可选参数 里面可以放置调试信息，Smart Ivr会打印到日志文件
     * @param string $value
     * @return $this
     */
    public function userMsg($value)
    {
        return $this->params('usermsg', $value);
    }

    /**
     * 可选参数 挂断原因根据sip信令设置
     * @param int $value
     * @return $this
     */
    public function cause($value)
    {
        return $this->params('cause', $value);
    }


}