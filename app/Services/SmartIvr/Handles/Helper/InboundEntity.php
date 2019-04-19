<?php
/**
 * Created by PhpStorm.
 * User: telrobot
 * Date: 2019/1/15
 * Time: 13:27
 */

namespace App\Services\SmartIvr\Handles\Helper;

use App\Services\SmartIvr\Contracts\ModelContract;
use Illuminate\Contracts\Support\Arrayable;

/**
 * 呼入数据实体类
 * Class InboundEntity
 * @package App\Services\SmartIvr\Handles\Helper
 */
class InboundEntity implements Arrayable
{
    public $request;

    public $taskId;

    public $flowid;

    public $scriptGroupId;

    public $callid;

    public $duration;

    public $callee;

    public $caller;

    public $errorCode;

    public $gender;

    public $hangupAt;

    /**
     * 转接数据
     * @var array $bridge
     */
    public $bridge;

    /**
     * @var ModelContract $model
     */
    public $model;

    public function setTaskId($value)
    {
        $this->taskId = $value;
        return $this;
    }

    public function setFlowid($value)
    {
        $this->flowid = $value;
        return $this;
    }

    public function setScriptGroupId($value)
    {
        $this->scriptGroupId = $value;
        return $this;
    }

    public function setCallid($value)
    {
        $this->callid = $value;
        return $this;
    }

    public function setDuration($value)
    {
        $this->duration = $value;
        return $this;
    }

    public function setCaller($value)
    {
        $this->caller = $value;
        return $this;
    }

    public function setCallee($value)
    {
        $this->callee = $value;
        return $this;
    }

    public function setRequest($value)
    {
        $this->request = $value;
        return $this;
    }

    public function setModel(ModelContract $model)
    {
        $this->model = $model;
        return $this;
    }

    public function toArray()
    {
        return [
            'flowid' => $this->flowid,
            'callid' => $this->callid,
            'task_id' => $this->taskId,
            'script_group_id' => $this->scriptGroupId,
            'score' => 0,
            'callee' => $this->callee,
            'caller' => $this->caller,
            'duration' => $this->duration,
            'gender' => null,
            'level_name' => null,

            'hangup_at' => null,
            'record_file' => null,
            'hangup_disposition' => null,//谁先挂断电话，机器人1，客户2
            'rounds' => null, //交互次数

            'bridge_callid' => null,
            'bridge_number' => null,
            'bridge_calldate' => null,
            'bridge_answerdate' => null,
        ];
    }

}
