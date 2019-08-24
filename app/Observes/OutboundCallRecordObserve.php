<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/8/25
 * Time: 2:29
 */

namespace App\Observes;


use App\Jobs\PostOutboundCallRecordJob;
use App\Models\OutboundCallRecord;

class OutboundCallRecordObserve
{
    public function creating(OutboundCallRecord $callRecord)
    {
        dispatch(new PostOutboundCallRecordJob($callRecord));
    }
}