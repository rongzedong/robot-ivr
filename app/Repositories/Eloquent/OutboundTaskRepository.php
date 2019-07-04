<?php

namespace App\Repositories\Eloquent;

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


}
