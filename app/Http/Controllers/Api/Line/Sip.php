<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/6/22
 * Time: 20:32
 */

namespace App\Http\Controllers\Api\Line;


use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;


class Sip extends Controller
{
    /**
     * 注册 SIP 线路
     * @param Request $request
     */
    public function register(Request $request)
    {

    }

    /**
     * 通过网关销毁SIP线路
     * @param $gateway
     */
    public function destroy($gateway)
    {

    }
}