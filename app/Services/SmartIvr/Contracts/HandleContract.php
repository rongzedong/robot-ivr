<?php
/**
 * Created by PhpStorm.
 * User: yangx
 * Date: 2018/12/3
 * Time: 11:21
 */

namespace App\Services\SmartIvr\Contracts;


use App\Services\SmartIvr\Handles\GetPayload\BridgeFailedFlow;
use App\Services\SmartIvr\Handles\GetPayload\ParseAsr;
use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use App\Services\SmartIvr\Handles\Helper\Intervene;
use App\Services\SmartIvr\Payload\Hangup;
use App\Services\SmartIvr\Payload\PlayAfterHangup;
use App\Services\SmartIvr\Payload\Playback;
use Illuminate\Database\Eloquent\Model;

interface HandleContract
{
    /**
     * @return HandleEntity
     */
    public function getHandleEntity();

    public function getRepository();

    /**
     * 设置/获取通话场景
     * @param null $prompt
     * @return $this|string
     */
    public function scene($prompt = null);

    /**
     * 获取常规话术
     *
     * @param true $isRecord 是否同时记录使用次数
     * @param false $reset 是重置使用次数
     * @return ModelContract|null
     */
    public function getNormal($isRecord = true, $reset = false);

    /**
     * 获取挂断话术-随机
     *
     * @return ModelContract|null
     */
    public function firstSpecialHangup();

    /**
     * 判断无法识别有效声音时是否设置处理话术
     * @return bool
     */
    public function hasSpecialInvalid();

    /**
     * 获取无法识别有效声音时
     *
     * @param false $is_record 是否同时记录使用次数
     * @param false $reset 是重置使用次数
     * @return Model|null
     */
    public function getPayloadInvalid($is_record = true, $reset = false);


    /**
     * 执行挂断操作
     *
     * @param Model $model
     * @return Hangup|PlayAfterHangup
     */
    public function hangup($model = null);


    /**
     * 设置/获取 当前可使用的话术
     * @param ModelContract $model
     * @return ModelContract
     */
    public function currentVerbalTrick($model = null);

    /**
     * 话术使用记录
     *
     * @param ModelContract $model
     * @param bool $isRecord 是否需要记录使用次数
     * @param false $reset 是重置使用次数
     * @param string $recordKey 缓存的键
     * @return ModelContract
     */
    public function useRecord(ModelContract $model, $isRecord = true, $reset = false, $recordKey = null);

    public function clear();

    /**
     * 分析回复
     * @param string $message 用户询问
     * @return ParseAsr
     */
    public function parse($message);

    /**
     * 标记用户说话的状态
     * @param bool $is 状态
     * @return mixed
     */
    public function userSpeak($is = true);

    /**
     * 判断用户是否说了话
     * @return mixed
     */
    public function isSpeak();

    /**
     * 转接状态
     * @param $status
     * @return bool
     */
    public function bridgeStatusIs($status);

    /**
     * 设置转接状态
     * @param $status
     * @return $this
     */
    public function setBridgeStatus($status);

    /**
     * 获取转接状态
     * @return int
     */
    public function getBridgeStatus();

    /**
     * 设置是否主动询问
     * @param $value
     * @return $this
     */
    public function setEnquire($value);

    /**
     * 是否主动询问
     * @return bool
     */
    public function isEnquire();

    /**
     * 介入管理对象
     * @return Intervene
     */
    public function intervene();

    /**
     * 进入下一流程
     * @param NotifyContract $notify
     * @param bool $enquire 是否主动询问
     * @return Hangup|PlayAfterHangup|Playback
     */
    public function nextFlow(NotifyContract $notify, $enquire = null);

    /**
     * 转接失败后处理流程
     * @return BridgeFailedFlow
     */
    public function bridgeFailedFlow();

    /**
     * 获取当前通话的ASR配置
     * @return string|null
     */
    public function getAsrConfigFilename();

    /**
     * 最后处理 payload
     * @param PayloadContract $payload
     * @return mixed
     */
    public function finalTreatmentPayload(PayloadContract $payload);


}