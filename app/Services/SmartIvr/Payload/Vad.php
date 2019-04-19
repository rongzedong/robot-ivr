<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 23:23
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\PayloadContract;

class Vad extends PayloadContract
{
    protected function init()
    {
        $this->action('asr')
            ->prompt($this->model->getReplyContent($this->handleEntity))
            ->maxWaitingMs(5000)
            ->minPauseMs(600)
            ->retry(0)
            ->mode(0)
            ->disableAsr(true);
    }


    public function disableAsr($value = true)
    {
        return $this->params('disable_asr', $value);
    }

    public function mode($value = 0)
    {
        return $this->params('mode', $value);
    }

    public function retry($value = 0)
    {
        return $this->params('retry', $value);
    }

    public function maxWaitingMs($value = 5000)
    {
        return $this->params('max_waiting_ms', $value);
    }

    public function minPauseMs($value = 600)
    {
        return $this->params('min_pause_ms', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function prompt($value)
    {
        return $this->params('prompt', $value);
    }
}