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

    public function index()
    {
        return $this->repository->get();
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }
}