<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 23:23
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\PayloadContract;
use App\Services\SmartIvr\Contracts\Playable;

/**
 * 开启asr 后 播放语音
 * Class AsrPlayback
 * @package App\Services\SmartIvr\Payload
 */
class AsrPlayback extends PayloadContract implements Playable
{
    protected function init()
    {
        $this->action('start_asr')
            ->params([
                'min_speak_ms' => config('smartivr.asr.start_asr_params.min_speak_ms', 100),
                'max_speak_ms' => config('smartivr.asr.start_asr_params.max_speak_ms', 10000),
                'min_pause_ms' => config('smartivr.asr.start_asr_params.min_pause_ms', 600),
                'max_pause_ms' => config('smartivr.asr.start_asr_params.max_pause_ms', 800),
                'pause_play_ms' => config('smartivr.asr.start_asr_params.pause_play_ms', 500), //0:关闭自动打断
                'threshold' => 0,
                'recordpath' => $this->recordPath(),
                'volume' => config('smartivr.asr.start_asr_params.volume', 80),
                'filter_level' => config('smartivr.asr.start_asr_params.filter_level', 0.2),//过滤噪音 0 - 1 之间
            ])
            ->afterIgnoreError(false)
            ->afterAction('playback')
            ->prompt($this->model->getReplyContent($this->handleEntity))
            ->wait($this->model->wait())
            ->retry($this->model->retry())
            ->allowInterrupt($this->model->allowInterrupt())
            ->blockAsr($this->model->blockAsr())
            ->setEnquire()
            ->setContext();

    }

    /**
     * 设置 asr 接口配置文件
     * @param $filename
     * @return $this
     */
    public function asrConfigureFilename($filename)
    {
        $this->params('asr_configure_filename', $filename);
        return $this;
    }

    public function ttsConfigureFilename($filename)
    {
        $this->afterParams('asr_configure_filename', $filename);
        return $this;
    }

    /**
     * 设置用户说话打断主意播放的阀值
     * @param int $value 设置0 停止用户说话打断语音播放功能
     * @return $this
     */
    public function pausePlayMs($value)
    {
        return $this->params('pause_play_ms', $value);
    }

    /**
     * 重播次数。就是wait时间内用户不说话，就重新播放声音
     * @param int $value
     * @return $this
     */
    public function retry($value = 0)
    {
        return $this->afterParams('retry', $value);
    }

    /**
     * 单位毫秒，放音结束后等待时间
     * @param int $value
     * @return $this
     */
    public function wait($value = 3000)
    {
        return $this->afterParams('wait', $value);
    }

    /**
     * 回复的内容
     * @param $value
     * @return $this
     */
    public function prompt($value)
    {
        return $this->afterParams('prompt', $value);
    }

    /**
     * 话音的识别情况设置
     *  -1 => 不识别
     *  0 =>  识别
     *  n => n毫秒以后开始识别
     * @param int $ms
     * @return $this
     */
    public function blockAsr($ms = -1)
    {
        return $this->afterParams('block_asr', $ms);
    }

    /**
     * 自动打断 本次放音多久后可打断
     *
     * @param $value
     * @return $this
     */
    public function allowInterrupt($value)
    {
        return $this->afterParams('allow_interrupt', $value);
    }


}