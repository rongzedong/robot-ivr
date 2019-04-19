<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 1:14
 */

namespace App\Services\SmartIvr;


use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Exceptions\SmartIvrBadParamException;
use App\Services\SmartIvr\Payload\Noop;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class SmartIvr implements Responsable
{
    /**
     * @var ReceiveData $receiveData
     */
    protected $receiveData;

    /**
     * 接收并验证请求参数
     * @param $data
     * @return $this
     * @throws SmartIvrBadParamException
     */
    public function receive($data)
    {
        if ($data instanceof Arrayable) {
            $this->receiveData = ReceiveData::make($data->toArray());
        } elseif (is_array($data)) {
            $this->receiveData = ReceiveData::make($data);
        } else {
            throw new SmartIvrBadParamException('[smartIvr]模块接收数据异常', 500);
        }

        //验证参数
        $this->validate($this->receiveData);

        return $this;
    }

    /**
     * 返回处理结果
     * @param $request
     * @return string
     * @throws \Throwable
     */
    public function toResponse($request)
    {
        return $this->parseNotify();
    }

    /**
     * @param ReceiveData $receiveData
     * @throws SmartIvrBadParamException
     */
    protected function validate(ReceiveData $receiveData)
    {
        //验证必要参数
        $required = [
            'notify', 'calleeid', 'callerid', 'callid',
        ];
        foreach ($required as $key) {
            if (!$receiveData->has($key)) {
                throw new SmartIvrBadParamException('IVR对接数据异常', 500);
            }
        }
    }


    /**
     * 解析通知
     * @return string
     * @throws SmartIvrBadParamException
     * @throws \Throwable
     */
    abstract public function parseNotify();


}