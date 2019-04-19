<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/9
 * Time: 0:34
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\PayloadContract;

class ConsolePlayback extends PayloadContract
{

    const PAUSE = 'pause';
    const RESUME = 'resume';
    const STOP = 'stop';

    protected function init()
    {
        $this->action('console_playback');
    }

    public function pause()
    {
        return $this->command(self::PAUSE);
    }

    public function resume()
    {
        return $this->command(self::RESUME);
    }

    public function stop()
    {
        return $this->command(self::STOP);
    }

    public function command($value)
    {
        return $this->params('command', $value);
    }
}