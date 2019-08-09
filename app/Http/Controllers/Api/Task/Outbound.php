<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/6/3
 * Time: 11:52
 */

namespace App\Http\Controllers\Api\Task;


use App\Http\Controllers\Api\Controller;
use App\Models\TimeGroup;
use App\Repositories\Eloquent\OutboundTaskRepository;
use Illuminate\Http\Request;

/**
 * 外呼任务管理
 * Class Outbound
 * @package App\Http\Controllers\Api\Task
 */
class Outbound extends Controller
{

    protected $outboundTask;

    public function __construct(OutboundTaskRepository $outboundTask)
    {
        $this->outboundTask = $outboundTask;
    }

    /**
     * @param Request $request
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        $this->outboundTask->updateOrCreate(['uuid' => $request->input('uuid')], $request->all());
        $this->outboundTask->updateOrCreateTimeGroup($request->input('time_group'));
        $this->outboundTask->updateOrCreateTimeRanges($request->input('time_ranges'));
    }

    /**
     * @param Request $request
     * @param string $id
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, $id)
    {
        $this->outboundTask->update($request->all(), $id);
        $this->outboundTask->updateOrCreateTimeGroup($request->input('time_group'));
        $this->outboundTask->updateOrCreateTimeRanges($request->input('time_ranges'));
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        $this->outboundTask->delete($id);
    }

    public function start($id)
    {
        $this->outboundTask->start($id);
    }

    public function stop($id)
    {
        $this->outboundTask->stop($id);
    }

}
