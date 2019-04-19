<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/22
 * Time: 1:10
 */

namespace App\Services\SmartIvr\Notify\Outbound;

use App\Services\SmartIvr\Events\Notify\Outbound\AsrMessagedEvent;
use App\Services\SmartIvr\Events\Notify\Outbound\AsrMessagingEvent;
use App\Services\SmartIvr\Handles\Helper\BridgeStatus;
use App\Services\SmartIvr\Handles\Outbound;
use App\Services\SmartIvr\Payload\ConsolePlayback;
use App\Services\SmartIvr\Payload\Hangup;
use App\Services\SmartIvr\Payload\Noop;


/**
 * 说话停顿max_speak_ms时间后返回的整句话的识别结果
 * Class AsrmessageNotify
 * @package App\Services\SmartIvr\Notify
 */
class AsrmessageNotify extends Notify
{

    protected $keyword;

    protected $question;

    /**
     * 入口
     * @return \App\Services\SmartIvr\Contracts\PayloadContract|AsrmessageNotify|Hangup|\App\Services\SmartIvr\Payload\PlayAfterHangup
     */
    public function handle()
    {
        $handle = $this->resolveHandle();
        if ($handle->bridgeStatusIs(BridgeStatus::PENDING)) {
            //转接中,返回无操作
            return new Noop();
        }

        AsrMessagingEvent::dispatch($this);

        $this->question = $question = $this->handleQuestion();


        if (empty($question) || $this->hasError()) {
            //噪音检测,且没有放音

            if ($this->checkInvalidStatus() && !$this->isPlayState()) {
                //有噪音，识别无效的情况
                $this->payload = $this->handle->getPayloadInvalid();
                //设置了对应话术时
                AsrMessagedEvent::dispatch($this);
                return $this->getPayload();
            }
            //恢复放音
            return (new ConsolePlayback())->resume();
        }

        //设置场景
        $handle->scene($this->getContext());

        $this->payload = $handle->parse($question)->getPayload($this);

        AsrMessagedEvent::dispatch($this);
        return $this->getPayload();
    }

    /**
     * 处理后的信息
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * 处理 识别客户询问内容
     * @return null|string
     */
    protected function handleQuestion()
    {
        $value = strtolower($this->receiveData->message);

        if (empty($value)) {
            return '';
        }
        $messages = collect(preg_split('/;/', $value, -1, PREG_SPLIT_NO_EMPTY));

        $messages = $messages->map(function ($value) {
            list($index, $question) = explode('.', $value, 2);
            return [
                'index' => $index,
                'question' => $question,
            ];
        });

        $this->index = $messages->max('index');

        return $messages->implode('question', ' ');
    }


}