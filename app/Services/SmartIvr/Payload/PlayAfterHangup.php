<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 1:57
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\Playable;

/**
 * Class PlayAfterHangup
 * 结束流程，播放一个提示声音，结束通话
 * @package App\Services\SmartIvr\Payload
 */
class PlayAfterHangup extends Playback implements Playable
{

    protected function init()
    {
        $this->action('playback')
            ->prompt($this->model->getReplyContent($this->handleEntity))
            ->wait(0)
            ->retry($this->model->retry())
            ->blockAsr(-1)
            ->afterAction('hangup')
            ->afterIgnoreError(true)
            ->cause(0);
    }

    /**
     * SIP 挂断原因
     * @param $value
     * @return $this
     */
    public function cause($value)
    {
        return $this->afterParams('cause', $value);
    }

    public function userMsg($value)
    {
        return $this->afterParams('usermsg', $value);
    }


}