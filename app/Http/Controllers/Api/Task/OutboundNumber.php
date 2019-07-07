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
     * @param Request $request
     * @param $task_id
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request, $task_id)
    {
        $this->outboundNumberRepository->setTask($task_id)->create($request->only([
            'id',
            'number',
            'description',
            'recycle',
            'recycle_limit',
            'time',
        ]));
    }

    /**
     * @param Request $request
     * @param $task_id
     * @param $id
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $task_id, $id)
    {
        $this->outboundNumberRepository->setTask($task_id)->update($request->only([
            'description',
            'recycle',
            'recycle_limit',
            'time',
        ]), $id);
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
