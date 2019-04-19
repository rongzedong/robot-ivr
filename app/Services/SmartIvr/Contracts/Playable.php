<?php
/**
 * Created by PhpStorm.
 * User: yangx
 * Date: 2018/12/13
 * Time: 10:06
 */

namespace App\Services\SmartIvr\Contracts;

interface Playable
{

    public function ttsConfigureFilename($filename);

    public function retry($value = 0);

    public function wait($value = 0);

    /**
     * 可选参数 提示文本 prompt 提示文本（
     * 如果最后4个字是.wav，就是录音文件放音，否则会调用TTS生成声音文件）
     * @param string|array $value
     * @return $this
     */
    public function prompt($value);

    /**
     * 话音时临时暂停ASR识别设置
     *  -1 => 不识别
     *  0 =>  禁用这个参数，不改变ASR状态。
     *  n => n毫秒以后开始识别，大于0 单位毫秒，放音前面多少秒不识别
     * @param int $ms
     * @return $this
     */
    public function blockAsr($ms);

    /**
     * 自动打断 本次放音多久后可打断
     *  -1 => 不识别
     *  0 =>  识别
     *  n => n毫秒以后开始识别
     * @param $value
     * @return $this
     */
    public function allowInterrupt($value);
}