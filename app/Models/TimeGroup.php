<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TimeGroup extends Model
{
    protected $table = 'autodialer_timegroup';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'name',
        'domain',
    ];


    /**
     * 关联 时间区间 (一对多)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeRange()
    {
        return $this->hasMany(TimeRange::class, 'group_uuid', 'uuid');
    }
}
