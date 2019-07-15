<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/15
 * Time: 18:51
 */

namespace App\Http\Controllers\Api\Task;


use App\Http\Controllers\Api\Controller;
use App\Repositories\Eloquent\OutboundCallRecordRepository;
use App\Repositories\Eloquent\OutboundNumberRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 通话记录
 * Class OutboundRecord
 * @package App\Http\Controllers\Api\Task
 */
class OutboundRecord extends Controller
{

    protected $repository;

    public function __construct(OutboundCallRecordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->repository->get();
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Request $request
     * @param OutboundNumberRepository $numberRepository
     * @param string $task_id
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request, OutboundNumberRepository $numberRepository, $task_id)
    {
        if ($request->call_id) {
            $data = $numberRepository->setTask($task_id)->skipPresenter()->scopeQuery(function ($model) use ($request) {
                return $model->where('callid', $request->call_id);
            })->first();
            if ($data) {
                $this->repository->updateOrCreate(['id' => $data['callid']], $data->toArray());
            }
        }

        abort(404);

    }

    /**
     * @param OutboundNumberRepository $numberRepository
     * @param string $task_id 任务ID
     * @param string $id 通话记录ID
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(OutboundNumberRepository $numberRepository, $task_id, $id)
    {
        $data = $numberRepository->setTask($task_id)->skipPresenter()->scopeQuery(function ($model) use ($id) {
            return $model->where('callid', $id);
        })->first();

        if ($data) {
            $this->repository->update($data->toArray(), $id);
        }

        abort(404);
    }
}