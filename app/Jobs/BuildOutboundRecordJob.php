<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/8/9
 * Time: 0:31
 */

namespace App\Jobs;


use App\Models\OutboundNumber;
use App\Repositories\Eloquent\OutboundCallRecordRepository;
use App\Repositories\Eloquent\OutboundNumberRepository;

/**
 * 扫描构建外呼通话记录
 * Class BuildOutboundRecordJob
 * @package App\Jobs
 */
class BuildOutboundRecordJob extends Job
{
    public $taskId;

    /**
     * BuildOutboundRecordJob constructor.
     * @param $taskId
     */
    public function __construct($taskId)
    {
        $this->taskId = $taskId;

    }

    /**
     * @param OutboundNumberRepository $repository
     * @param OutboundCallRecordRepository $callRecordRepository
     * @throws \Exception
     */
    public function handle(OutboundNumberRepository $repository, OutboundCallRecordRepository $callRecordRepository)
    {

        $repository->setTask($this->taskId)->skipPresenter()->scopeQuery(function ($model) {
            return $model->where('state', 10); //呼叫完成
        })->get()->each(function (OutboundNumber $data) use ($callRecordRepository) {
            $callRecordRepository->updateOrCreate(['id' => $data['callid'], 'task_id' => $this->taskId], $data->setHidden(['id', 'callid'])->toArray());
            //软删除外呼号码(防止重复扫描生成记录)
            $data->delete();
        });
    }

}