<?php

namespace App\Repositories\Eloquent;

use App\Models\TimeGroup;
use App\Models\TimeRange;
use Illuminate\Support\Arr;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\OutboundTask;

/**
 * Class OutboundTaskRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OutboundTaskRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OutboundTask::class;
    }


    public function start($task_id)
    {
        $this->find($task_id)->start();
    }

    public function stop($task_id)
    {
        $this->find($task_id)->stop();
    }

    public function updateOrCreateTimeGroup($group)
    {
        if ($group) {
            TimeGroup::updateOrCreate(['uuid' => Arr::get($group, 'uuid')], $group);
        }

    }

    public function updateOrCreateTimeRanges($ranges)
    {
        if ($ranges) {
            foreach ($ranges as $range) {
                TimeRange::updateOrCreate(['uuid' => Arr::get($range, 'uuid')], $range);
            }
        }


    }


}
