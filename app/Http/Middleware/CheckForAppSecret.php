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
     */
    public function handle($request, Closure $next)
    {

        return $next($request);
//        $app_id = $this->getRequestAppId($request);
//
//        $date = $this->getRequestDate($request);
//        empty($date) && abort(422, '缺少参数：date');
//
//        $sign = $this->getRequestSign($request);
//        empty($sign) && abort(422, '缺少参数：sign');
//
//        !hash_equals($this->generateSign($date, $app_id, config('app_secret')), $sign) && abort(422, '验签失败');
//
//        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getRequestAppId($request)
    {
        return object_get($request, 'app-key', $request->header('app-key'));
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
