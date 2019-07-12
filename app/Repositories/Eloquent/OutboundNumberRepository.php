<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Criteria\InCriteria;
use App\Repositories\Presenters\OutboundNumberPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\OutboundNumber;


/**
 * Class OutboundNumberRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class OutboundNumberRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id' => 'in',
        'callid',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OutboundNumber::class;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setTask($id)
    {
        OutboundNumber::setTableIdentification($id);
        return $this;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(InCriteria::class));
    }

    public function presenter()
    {
        return OutboundNumberPresenter::class;
    }

}
