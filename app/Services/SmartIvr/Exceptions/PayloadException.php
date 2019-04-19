<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/4/1
 * Time: 20:03
 */

namespace App\Services\SmartIvr\Exceptions;

use App\Services\SmartIvr\Contracts\PayloadContract;
use Exception;

/**
 * 用于正常的交互逻辑
 * Class PayloadException
 * @package App\Services\SmartIvr\Exceptions
 */
class PayloadException extends Exception
{

    protected $payload;

    public function __construct(PayloadContract $payload)
    {
        parent::__construct('', 200);

        $this->payload = $payload;

    }

    public function getPayload()
    {
        return $this->payload;
    }
}