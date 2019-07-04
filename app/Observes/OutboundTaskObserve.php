<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/4
 * Time: 15:08
 */

namespace App\Observes;

use App\Models\OutboundNumber;
use App\Models\OutboundTask;
use Illuminate\Support\Carbon;

/**
 * 自动外呼任务模型事件观察者
 * Class OutboundTaskObserve
 * @package App\Observes
 */
class OutboundTaskObserve
{
    public function created(OutboundTask $task)
    {
        $this->createTable($task->getKey());
    }

    public function updating(OutboundTask $task)
    {
        $task->alter_datetime = Carbon::now();
    }

    public function deleting(OutboundTask $task)
    {
        $this->dropTable($task->getKey());
    }

    /**
     * 创建数据表
     * @param $uuid
     */
    private function createTable($uuid)
    {
        $n = 0;
        $max = 3;
        do {
            $flat = OutboundNumber::createTable($uuid);
            $n++;
            sleep(2);
        } while ($n < $max && !$flat);

    }

    private function dropTable($uuid)
    {
        $n = 0;
        $max = 3;
        do {
            $flat = OutboundNumber::dropTable($uuid);
            $n++;
            sleep(2);
        } while ($n < $max && !$flat);

    }
}