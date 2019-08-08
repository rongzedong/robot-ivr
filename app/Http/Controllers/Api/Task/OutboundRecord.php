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

    public function index($task_id)
    {
        return $this->repository->findWhere(['task_id' => $task_id]);
    }

    public function show($task_id, $id)
    {
        return $this->repository->findWhere(['id' => $id, 'task_id' => $task_id]);
    }
}