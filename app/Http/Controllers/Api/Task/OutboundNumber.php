<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/6/3
 * Time: 16:56
 */

namespace App\Http\Controllers\Api\Task;


use App\Http\Controllers\Api\Controller;
use App\Repositories\Eloquent\OutboundNumberRepository;
use Illuminate\Http\Request;

/**
 * 外呼号码管理
 * Class OutboundNumber
 * @package App\Http\Controllers\Api\Task
 */
class OutboundNumber extends Controller
{
    protected $outboundNumberRepository;

    public function __construct(OutboundNumberRepository $outboundNumberRepository)
    {
        $this->outboundNumberRepository = $outboundNumberRepository;
    }

    public function index($task_id)
    {
        return $this->outboundNumberRepository->setTask($task_id)->get();
    }

    /**
     * 已开始呼叫的号码列表
     * @param $task_id
     * @return mixed
     */
    public function callStarted($task_id)
    {
        return $this->outboundNumberRepository->setTask($task_id)->scopeQuery(function ($model) {
            return $model->whereNotNull('state');
        })->orderBy('calldate', 'desc')->get();

    }


    /**
     * @param Request $request
     * @param $task_id
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request, $task_id)
    {
        $outboundNumberRepository = $this->outboundNumberRepository->setTask($task_id);
        $number = $outboundNumberRepository->find($request->id);

        if (is_null($number)) {
            $outboundNumberRepository->create($request->all());
        }
    }

    /**
     * @param Request $request
     * @param $task_id
     * @param $id
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $task_id, $id)
    {
        $this->outboundNumberRepository->setTask($task_id)->update($request->all(), $id);
    }

    /**
     * @param $task_id
     * @param $id
     */
    public function destroy($task_id, $id)
    {
        $this->outboundNumberRepository->setTask($task_id)->delete($id);
    }
}
