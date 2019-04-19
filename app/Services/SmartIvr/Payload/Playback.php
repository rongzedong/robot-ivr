<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/9
 * Time: 0:32
 */

namespace App\Services\SmartIvr\Payload;


use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Contracts\PayloadContract;
use App\Services\SmartIvr\Contracts\Playable;

/**
 * 播放语音载体
 * Class Playback
 * @package App\Services\SmartIvr\Payload
 */
class Playback extends PayloadContract implements Playable
{
    protected function init()
    {
        $this->action('playback')
            ->prompt($this->model->getReplyContent($this->handleEntity))
            ->wait($this->model->wait())
            ->retry($this->model->retry())
            ->blockAsr($this->model->blockAsr())
            ->allowInterrupt($this->model->allowInterrupt())
            ->setEnquire()
            ->setContext();
    }

    /**
     * tts 的转换配置
     * @param $filename
     * @return Playback
     */
    public function ttsConfigureFilename($filename)
    {
        return $this->params('tts_configure_filename', $filename);
    }

    /**
     * 临时关闭asr
     * @deprecated blockAsr
     * @param bool $value
     * @return PayloadContract|Playback
     */
    public function suspendAsr($value = true)
    {
        if ($value === true) {
            $this->wait(0)->retry(0);
        } else {
            if ($this->model instanceof ModelContract) {
                $this->wait($this->model->wait())->retry($this->model->retry());
            }
        }
        return parent::suspendAsr($value);
    }

    public function retry($value = 0)
    {
        return $this->params('retry', $value);
    }

    public function wait($value = 0)
    {
        return $this->params('wait', $value);
    }

    /**
     * 可选参数 提示文本 prompt 提示文本（
     * 如果最后4个字是.wav，就是录音文件放音，否则会调用TTS生成声音文件）
     * @param string|array $value
     * @return $this
     */
    public function prompt($value)
    {
        return $this->params('prompt', $value);
    }

    /**
     * 话音时临时暂停ASR识别设置
     *  -1 => 不识别
     *  0 =>  禁用这个参数，不改变ASR状态。
     *  n => n毫秒以后开始识别，大于0 单位毫秒，放音前面多少秒不识别
     * @param int $ms
     * @return $this
     */
    public function blockAsr($ms = 0)
    {
        return $this->params('block_asr', $ms);
    }

    /**
     * 自动打断 本次放音多久后可打断
     *  -1 => 不打断
     *  0 =>  打断
     *  n => n毫秒以后开始打断
     * @param $value
     * @return $this
     */
    public function allowInterrupt($value)
    {
        return $this->params('allow_interrupt', $value);
    }
}