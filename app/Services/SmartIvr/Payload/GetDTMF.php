<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/10
 * Time: 14:44
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\PayloadContract;

/**
 * 获取DTMF按键
 * Class GetDTMF
 * @package App\Services\SmartIvr\Payload
 */
class GetDTMF extends PayloadContract
{
    protected function init()
    {
        $this->action('getdtmf')
            ->prompt($this->model->getReplyContent($this->handleEntity))
            ->max(128)
            ->params([
                'invalid_prompt' => '按键无效',
                'min' => 0,
                'tries' => 1,
                'timeout' => 5000,
                'digit_timeout' => 3000,
                'terminators' => '#'
            ]);
    }

    public function prompt($value)
    {
        return $this->params('prompt', $value);
    }

    public function max($value)
    {
        return $this->params('max', $value);
    }

}