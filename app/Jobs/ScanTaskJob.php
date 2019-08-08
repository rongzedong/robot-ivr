<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/28
 * Time: 3:00
 */

namespace App\Jobs;


use App\Repositories\Eloquent\OutboundTaskRepository;
use Illuminate\Support\Facades\Artisan;

class ScanTaskJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param OutboundTaskRepository $repository
     * @return void
     */
    public function handle(OutboundTaskRepository $repository)
    {
        $tasks = $repository->get(['uuid', 'start']);

        $tasks->each(function ($task) {
            if ($task->start) {
                //失败自动重呼
                Artisan::call('auto-dialer:recycle', [
                    'task_id' => $task->uuid
                ]);
            }
            //生成外呼记录
            dispatch(new BuildOutboundRecordJob($task->uuid));
        });
    }
}