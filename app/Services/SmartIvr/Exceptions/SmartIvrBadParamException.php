<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/3/8
 * Time: 1:39
 */

namespace App\Services\SmartIvr\Exceptions;

use Psr\Log\LoggerInterface;
use Throwable;
use RuntimeException;
use App\Services\SmartIvr\Payload\Hangup;

class SmartIvrBadParamException extends RuntimeException
{

    /**
     * 参数错误时的响应处理
     * @return string
     */
    public function render()
    {
        return (string)new Hangup();
    }

    public function report()
    {
        if ($e = $this->getPrevious()) {
            $this->writeLog($e);
        }
    }

    protected function writeLog(Throwable $e)
    {
        try {
            $logger = app()->make(LoggerInterface::class);
        } catch (Throwable $ex) {
            throw $e; // throw the original exception
        }

        $logger->error(
            $e->getMessage(),
            ['exception' => $e]
        );
    }


}