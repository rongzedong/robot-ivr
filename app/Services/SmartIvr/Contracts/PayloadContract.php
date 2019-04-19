<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 22:23
 */

namespace App\Services\SmartIvr\Contracts;

use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use Illuminate\Support\Facades\Config;

/**
 * Class PayloadContract
 * @package App\Services\SmartIvr\Contracts
 */
abstract class PayloadContract
{
    protected $data;

    /**
     * @var ModelContract $model
     */
    protected $model;

    /**
     * @var HandleEntity $handleEntity
     */
    protected $handleEntity;

    public function __construct($entity = null, $model = null)
    {
        if ($entity instanceof HandleEntity) {
            $this->handleEntity = $entity;
        }

        if ($model instanceof ModelContract) {
            $this->model = $model;
        }

        $this->init();
    }

    protected function init()
    {
    }

    public function setModel(ModelContract $model)
    {
        $this->model = $model;
    }

    /**
     * @return ModelContract|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * 返回ASR通话语音路径
     * @return string
     */
    public function recordPath()
    {
        return Config::get('filesystems.disks.smart_ivr.root') . '/asrdir/' . now()->format('Ymd');
    }

    /**
     * 执行的动作名
     * @param string $value
     * @return $this
     */
    protected function action($value)
    {
        $this->data['action'] = $value;
        return $this;
    }

    /**
     * action的参数，具体参考具体动作
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    protected function params($key, $value = '')
    {
        if (is_array($key)) {
            $this->data['params'] = $key;
        } else {
            $this->data['params'][$key] = $value;
        }
        return $this;
    }

    /**
     * 如果action执行失败，是否继续执行after_action
     * @param bool $value
     * @return $this
     */
    protected function afterIgnoreError($value)
    {
        $this->data['after_ignore_error'] = $value;
        return $this;
    }

    /**
     * 可选参数 用于连续执行2个动作，比如playback后执行挂机。
     * @param string|array $value
     * @return $this
     */
    protected function afterAction($value)
    {
        $this->data['after_action'] = $value;
        return $this;
    }

    /**
     * 可选参数
     * 如果之前已经执行了start_asr，
     * 通过通过这个参数，来暂停停用ASR，
     * 比如希望本次放音(playback)，不要执行ASR，就可以把这个参数设置true.
     * @param bool $value
     * @return $this
     */
    protected function suspendAsr($value = true)
    {
        $this->data['suspend_asr'] = $value;
        return $this;
    }

    /**
     * 可选参数 after_action的参数内容
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    protected function afterParams($key, $value = '')
    {
        if (is_array($key)) {
            $this->data['after_params'] = $key;
        } else {
            $this->data['after_params'][$key] = $value;
        }
        return $this;
    }

    /**
     * 流程数据
     * @param null $key
     * @param mixed $value
     * @return $this
     */
    public function flowData($key = null, $value = null)
    {
        if (is_array($key)) {
            $this->data['flowdata'] = $key;
        } else {
            $this->data['flowdata'][$key] = $value;
        }
        return $this;
    }

    /**
     * 设置场景
     * @param string $value
     * @return $this
     */
    public function setContext($value = '')
    {
        if (empty($value) && $this->model) {
            $this->data['flowdata']['context'] = $this->model->getKey();
        } else {
            $this->data['flowdata']['context'] = $value;
        }
        return $this;
    }

    /**
     * 设置主动询问状态
     */
    public function setEnquire($value = null)
    {
        if (is_null($value) && $this->model) {
            $this->data['flowdata']['enquire'] = $this->model->enquire();
        } else {
            $this->data['flowdata']['enquire'] = $value;
        }
        return $this;
    }

    /**
     * 返回所有数据
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * 返回 JSON 响应数据
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function __toString()
    {
        return $this->toJson();
    }

}