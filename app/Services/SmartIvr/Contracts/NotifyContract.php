<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/22
 * Time: 1:16
 */

namespace App\Services\SmartIvr\Contracts;

use App\Services\SmartIvr\Exceptions\SmartIvrBadParamException;
use App\Services\SmartIvr\ReceiveData;

/**
 * 通知类型
 * Interface NotifyContract
 * @package App\Services\SmartIvr\Contracts
 */
abstract class NotifyContract
{
    protected $receiveData;

    protected $payload;

    protected $handleClass;
    /**
     * @var HandleContract
     */
    protected $handle;

    protected $index = 0;


    public function __construct(ReceiveData $receiveData)
    {
        $this->receiveData = $receiveData;
        $this->init();
    }

    protected function init()
    {
    }

    /**
     * 返回待处理实体
     * @return mixed
     */
    protected abstract function getHandleEntity();

    /**
     * 获取 话术处理类
     * @return HandleContract
     */
    public function resolveHandle()
    {
        if (empty($this->handleClass)) {
            throw new SmartIvrBadParamException('未设置话术处理类 HandleContract');
        }
        if (empty($this->handle)) {
            $this->handle = new $this->handleClass($this->getHandleEntity());
        }

        return $this->handle;
    }

    /*
     * 通知处理方式
     */
    public abstract function handle();

    /**
     * 获取当前请求对象
     * @return ReceiveData
     */
    public function getReceiveData()
    {
        return $this->receiveData;
    }

    /**
     * 获取当前话术负载对象
     * @return PayloadContract
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * 主叫号码
     * @return mixed
     */
    public function getCallerId()
    {
        return $this->receiveData->origcallerid;
    }

    /**
     * @return string 被叫号码
     */
    public function getCalleeId()
    {
        return $this->receiveData->callerid;
    }

    /**
     * 获取任务使用的话术分组
     * @return int
     */
    public function getTaskTemplateGroup()
    {
        return $this->receiveData->calleeid;
    }

    /**
     * 通话ID
     * @return string
     */
    public function getCallId()
    {
        return $this->receiveData->callid;
    }

    /**
     * 获取缓存通话记录 键key
     * @return string
     */
    public function getCallRecordKey()
    {
        return $this->getCallId();
    }

    /**
     * 获取上文应答话术
     * @return string 关键词id
     */
    public function getContext()
    {
        return $this->receiveData->flowdata['context'] ?? '';
    }

    /**
     * 任务id
     * @return string
     */
    public function getTaskId()
    {
        return $this->receiveData->flowid;
    }

    /**
     * 获取是否发起主动询问
     * @return bool
     */
    public function getEnquire()
    {
        return $this->receiveData->flowdata['enquire'] ?? false;
    }

    /**
     * 获取错误码
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->receiveData->errorcode;
    }

    /**
     * 是否有错误
     * @return bool
     */
    public function hasError()
    {
        return $this->getErrorCode() != 0;
    }

    /**
     * 电话是否已挂断
     * @return bool
     */
    public function isHangup()
    {
        return $this->receiveData->hangup === true || $this->receiveData->hangup == 'true' ? true : false;
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
     *
     * @return int
     */
    public function getSpeakMs()
    {
        return $this->receiveData->speakms ?? 0;
    }

    /**
     * 用户说完话时的放音状态
     * @return boolean
     */
    public function isPlayState()
    {
        return $this->receiveData->playstate ?? false;
    }

    /**
     * 噪音处理状态
     * @return boolean
     */
    public function checkInvalidStatus()
    {
        return $this->receiveData->flowdata['invalid'] ?? false;
    }

    /**
     * 获取用户说的话
     * @return string
     */
    public function getQuestion()
    {
        return strtolower($this->receiveData->message);
    }

    /**
     * 回复 序号
     * @return int
     */
    public function getIndex()
    {
        return (int)$this->index;
    }

}