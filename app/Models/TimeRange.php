<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeRange extends Model
{
    protected $table = 'autodialer_timerange';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;


    protected $fillable = [
        'uuid', 'begin_datetime', 'end_datetime', 'group_uuid'
    ];

    /**
     * 关联 时间组 （多对一）
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeGroup()
    {
        return $this->belongsTo(TimeGroup::class, 'group_uuid', 'uuid');
    }

    public function getFullTimeAttribute()
    {
        return $this->attributes['begin_datetime'] . '~' . $this->attributes['end_datetime'];
    }


}
