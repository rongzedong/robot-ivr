<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/1/9
 * Time: 1:37
 */

namespace App\Services\SmartIvr\Handles\Helper;

/**
 * Class HandleEntity
 * @package App\Services\SmartIvr\Handles\Helper
 */
class HandleEntity
{
    public $scriptGroup;
    public $callId;
    public $taskId;

    /**
     * @var string $lineNo 线路号码
     */
    public $lineNo;

    /**
     * @var string $orderNo 客户号码
     */
    public $orderNo;

    public function setScriptGroup($value)
    {
        $this->scriptGroup = $value;
        return $this;
    }

    public function setCallId($value)
    {
        $this->callId = $value;
        return $this;
    }

    public function setTaskId($value)
    {
        $this->taskId = $value;
        return $this;
    }

    /**
     * 线路号码
     * @param $value
     * @return $this
     */
    public function setLineNo($value)
    {
        $this->lineNo = $value;
        return $this;
    }

    /**
     * 设置
     * @param $value
     * @return $this
     */
    public function setOrderNo($value)
    {
        $this->orderNo = $value;
        return $this;
    }
}