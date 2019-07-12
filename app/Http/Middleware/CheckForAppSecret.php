<?php

namespace App\Http\Middleware;

use App\Models\AppSecret;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForAppSecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Throwable
     */
    public function handle($request, Closure $next)
    {
        try {

            $date = $this->getRequestDate($request);
            empty($date) && abort(422, '未授权');

            $sign = $this->getRequestSign($request);
            empty($sign) && abort(422, '未授权');

            !hash_equals($this->generateSign($date, config('app.ivr_key'), config('app.ivr_secret')), $sign) && abort(422, '验签失败');

            $result = $next($request);

            return $result ?: 'SUCCESS';
        } catch (\Throwable $e) {
            throw $e;
        }

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getRequestSign($request)
    {
        return object_get($request, 'sign', $request->header('sign'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getRequestDate($request)
    {
        return object_get($request, 'datetime', $request->header('datetime'));
    }

    /**
     * 加密
     * 规则：appKey + "\n" + GMT时间
     *
     * @param String $date 时间：Thu, 13 Dec 2018 01:27:17 GMT
     * @param String $appKey
     * @param String $appSecret
     * @return string
     */
    private function generateSign(String $date, String $appKey, String $appSecret)
    {
        if (function_exists('hash_hmac')) {
            $stringToSign = $appKey . "\n" . $date;
            return base64_encode(hash_hmac("sha1", $stringToSign, $appSecret, true));
        }
        return '';
    }


}
