<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/14
 * Time: 21:48
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OutboundCallRecord extends Model
{
    protected $table = 'outbound_call_records';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'task_id',
        'number',
        'state',
        'status',
        'description',
        'calldate',
        'bill',
        'duration',
        'hangupcause',
        'hangupdate',
        'answerdate',
        'recordfile',
        'calleridnumber',
        'bridge_callid',
        'bridge_number',
        'bridge_calldate',
        'bridge_answerdate',
    ];
}