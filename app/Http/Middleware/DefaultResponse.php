<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/8/4
 * Time: 18:44
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class DefaultResponse
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
        /** @var Response $result */
        $result = $next($request);
        return !is_null($result->getOriginalContent()) ? $result : response('SUCCESS')->withHeaders([
            'Content-Type' => 'text/plain'
        ]);
    }
}