<?php
/**
 * Created by PhpStorm.
 * User: yangx
 * Date: 2018/12/5
 * Time: 16:37
 */

namespace App\Services\SmartIvr\Contracts;

use App\Services\SmartIvr\Handles\Helper\HandleEntity;

/**
 * 数据模型接口
 * Interface ModelContract
 * @package App\Services\SmartIvr\Contracts
 */
interface ModelContract
{
    /**
     * @param $key
     * @param bool $reset
     * @return mixed
     */
    public function useCallRecord($key, $reset = false);

    /**
     * @param $key
     * @return mixed
     */
    public function filterUsedCallRecord($key);

    /**
     * 判断词性是常规词条
     * return bool
     */
    public function isWordClassOfNormal();

    public function isWordClassOfQuery();

    public function isWordClassOfYes();

    public function isWordClassOfNo();

    public function isWordClassOfSpecial();

    /**
     * 动作
     * @return mixed
     */
    public function isActionRebroadcast();

    public function isActionNext();

    public function isActionHangup();

    public function isActionBridge();

    public function isActionDefault();

    /**
     * 获取回复语音
     * @param HandleEntity $entity 通话实体
     * @return mixed
     */
    public function getReplyContent($entity);

    /**
     * 获取回复的文本信息
     * @param HandleEntity $entity
     * @return string
     */
    public function getReplyMessage($entity);

    /**
     * 返回 转接背景音乐文件地址
     * @return string
     */
    public function getBridgeBackground();


    public function enquire();

    /**
     * 返回等待用户回应的毫秒数
     * @return int
     */
    public function wait();

    /**
     * 重复播放次数
     * @return int
     */
    public function retry();

    /**
     * 多少毫秒后允许识别
     * -1 = 不识别,
     * @return int
     */
    public function blockAsr();

    /**
     * 本次放音是否允许自动打断
     * 用户说话超过 设置时间 打断
     * @return mixed
     */
    public function allowInterrupt();

    /**
     * 获取主键
     * @return mixed
     */
    public function getKey();

}