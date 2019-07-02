<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/6/3
 * Time: 11:52
 */

namespace App\Http\Controllers\Api\Task;


use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

/**
 * 外呼任务管理
 * Class Outbound
 * @package App\Http\Controllers\Api\Task
 */
class Outbound extends Controller
{

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        info('store task:', $request->all());
    }

    /**
     * @param Request $request
     * @param string $id
     */
    public function update(Request $request, $id)
    {
        info('update task:', $request->all());
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {

    }

    public function start($id)
    {

    }

    public function stop($id)
    {

    }

}
