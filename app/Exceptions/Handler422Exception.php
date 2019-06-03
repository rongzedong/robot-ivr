<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/3/7
 * Time: 14:58
 */

namespace App\Exceptions;


use Illuminate\Support\Facades\Auth;
use Psr\Log\LoggerInterface;
use Throwable;
use Exception;

class Handler422Exception extends \LogicException
{

    public function __construct(string $message = "", int $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return response()->json($this->message, $this->code);
    }

    public function report()
    {
        if ($e = $this->getPrevious()) {
            $this->writeLog($e);
        }
    }

    protected function writeLog(Exception $e)
    {
        try {
            $logger = app()->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $e; // throw the original exception
        }

        $logger->error(
            $e->getMessage(),
            array_merge($this->context(), ['exception' => $e]
            ));
    }

    /**
     * Get the default context variables for logging.
     *
     * @return array
     */
    protected function context()
    {
        try {
            return array_filter([
                'userId' => Auth::id(),
                'email' => Auth::user() ? Auth::user()->email : null,
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }
}