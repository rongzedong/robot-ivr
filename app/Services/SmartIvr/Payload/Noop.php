<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 1:57
 */

namespace App\Services\SmartIvr\Payload;

use App\Services\SmartIvr\Contracts\PayloadContract;

/**
 * 不需要执行任何操作
 * Class Noop
 * @package App\Services\SmartIvr\Payload
 */
class Noop extends PayloadContract
{

    protected function init()
    {
        $this->action('noop');
    }

    /**
     * 打印调试信息到日志文件
     * @param $value
     * @return mixed
     */
    public function usermsg($value)
    {
        return $this->params('usermsg', $value);
    }


}