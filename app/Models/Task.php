<?php

namespace App\Models\AutoDialer;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * 外呼任务表
 * Class Task
 * @package App\Models\AutoDialer
 * @author Xingshun <250915790@qq.com>
 */
class Task extends Model
{
    protected $table = 'autodialer_task';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    protected $dates = [
        'create_datetime', 'alter_datetime'
    ];

    protected $fillable = [
        'name',
        'create_datetime',
        'alter_datetime',
        'start',
        'maximumcall',
        'recycle_limit',
        'random_assignment_number',
        'disable_dial_timegroup',
        'destination_extension', //外呼话术分组id 也是 httpapi 接口 calledid
        'destination_dialplan',
        'destination_context',
        'scheduling_policy_ratio',
        'scheduling_queue',
        'dial_format',
        'domain',
        'remark',
        'originate_variables',
        '_originate_timeout',
        '_origination_caller_id_number',
        'user_id',
        'caller_line_id',
        'customer_service_id', //转接客服组id
        'sort',
        'call_per_second',
    ];

    protected $casts = [
        'destination_extension' => 'integer',
        '_origination_caller_id_number' => 'string',
        'originate_variables' => 'string',
        'maximumcall' => 'integer',
        'start' => 'integer',
        'stop' => 'integer',
    ];



}
