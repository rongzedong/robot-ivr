<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/10
 * Time: 15:49
 */

namespace App\Services\SmartIvr\Payload;

use App\Services\SmartIvr\Contracts\Bridgeable;
use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Contracts\PayloadContract;

/**
 * 转接到电话号码,缺少必要的参数后挂断
 * Class Bridge
 * @package App\Services\SmartIvr\Payload
 */
class Bridge extends PayloadContract
{

    protected $bridgeNumber;

    /**
     * Bridge constructor.
     * @param null $entity
     * @param null $model
     * @param null $bridge_number
     */
    public function __construct($entity = null, $model = null, $bridge_number = null)
    {
        $this->bridgeNumber = $bridge_number;
        parent::__construct($entity, $model);
    }


    protected function init()
    {
        if ($this->model) {
            if ($this->model instanceof Bridgeable) {
                $bridge_params = $this->model->getBridgeParams($this->handleEntity->taskId, $this->bridgeNumber);
            }

            if ($bridge_params) {
                $this->action('bridge')
                    ->prompt($this->model->getReplyContent($this->handleEntity))
                    ->number(array_get($bridge_params, 'number'))
                    ->callerId(array_get($bridge_params, 'caller_id'))
                    ->gateway(array_get($bridge_params, 'gateway'))
                    ->background($this->model->getBridgeBackground());
            } else {
                //不能转换挂断
                $this->action('playback')
                    ->params('prompt', $this->model->getReplyContent($this->handleEntity))
                    ->params('wait', 0)
                    ->params('retry', $this->model->retry())
                    ->params('block_asr', -1)
                    ->afterIgnoreError(true)
                    ->afterAction('hangup')
                    ->afterParams('cause', 0)
                    ->afterParams('usermsg', 'bridge-playback-hangup');
            }
        } else {
            //直接挂断
            $this->action('hangup')->params('cause', 0)->params('usermsg', 'bridge-hangup');
        }

    }

    /**
     * 被叫号码，如果gateway没设置，必须是完整呼叫串类似:sofia/external/电话号码@网关Ip
     * @param $value
     * @return Bridge
     */
    public function number($value)
    {
        return $this->params('number', $value);
    }

    /**
     * 提示语音
     * @param $value
     * @return $this
     */
    public function prompt($value)
    {
        return $this->params('prompt', $value);
    }

    /**
     * 可选 主叫号码（对方看到的来电显示）
     * @param $value
     * @return Bridge
     */
    public function callerId($value)
    {
        return $this->params('callerid', $value);
    }

    /**
     * 可选 网关名
     * @param $value
     * @return Bridge
     */
    public function gateway($value)
    {
        return $this->params('gateway', $value);
    }

    /**
     * 可选 背景音乐，最好大于1分钟
     * @param $value
     * @return $this
     */
    public function background($value)
    {
        return $this->params('background', $value);
    }


}