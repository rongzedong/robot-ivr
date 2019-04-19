<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/22
 * Time: 1:10
 */

namespace App\Services\SmartIvr\Notify\Inbound;

use App\Services\SmartIvr\Events\Notify\Inbound\AsrProgressedEvent;
use App\Services\SmartIvr\Events\Notify\Inbound\AsrProgressingEvent;
use App\Services\SmartIvr\Payload\ConsolePlayback;
use App\Services\SmartIvr\Payload\Noop;

/**
 * 说话停顿min_speak_ms时间后返回的识别结果
 * Class Leave
 * @package App\Services\SmartIvr\Notify
 */
class AsrprogressNotify extends Notify
{

    /**
     * @var bool 噪音
     */
    protected $invalid = false;

    /**
     * @var bool 是否打断放音
     */
    protected $break = false;


    public function handle()
    {
        AsrProgressingEvent::dispatch($this);

        if ($this->isHangup()) {
            //已挂断
            $this->payload = new Noop();
            AsrProgressedEvent::dispatch($this);
            return $this->getPayload();
        }
        $handle = $this->resolveHandle();
        //获取本次ASR识别结果（用户短暂说话内容）
        $question = $this->getQuestion();

        //设置用户说话标记
        $handle->userSpeak();

        if (empty($question) || $this->hasError()) {
            //没有放音的时候,噪音检测
            if ($this->getPlayMs() == 0 && $handle->hasSpecialInvalid()) {
                //有配置了防噪音话术
                //设置存在无效声音，噪音处理标识
                $this->invalid = true;
                //打断语音播放
                $this->break = true;
            } else {
                $this->payload = (new ConsolePlayback())->resume();
                AsrProgressedEvent::dispatch($this);
                return $this->getPayload();
            }
        } else {
            if ($this->getPlayMs() > 0) {
                $this->keywordPause();
            } else {
                //没有放音
                $this->payload = new Noop();
                AsrProgressedEvent::dispatch($this);
                return $this->getPayload();
            }

        }

        if ($this->break) {
            //暂停语音播放
            $this->payload = (new ConsolePlayback())->pause();
            //上下文数据
            $this->payload
                ->setEnquire($this->getEnquire())
                ->setContext($this->getContext())
                ->flowData('invalid', $this->invalid);
        } else {
            $this->payload = (new ConsolePlayback())->resume();
        }

        AsrProgressedEvent::dispatch($this);
        return $this->getPayload();


    }

    /**
     * 返回用户说话时，放音时间（毫秒）
     * return int
     */
    public function getPlayMs()
    {
        return $this->receiveData->playms;
    }

    /**
     * 关键词打断
     */
    private function keywordPause()
    {
        //设置场景
        $this->handle->scene($this->getContext());
        if ($this->handle->parse($this->getQuestion())->has()) {
            //匹配到关键词，打断语音播放
            $this->break = true;
        }
    }


}