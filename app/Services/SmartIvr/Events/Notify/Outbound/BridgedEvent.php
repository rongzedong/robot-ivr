<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/12/18
 * Time: 16:27
 */

namespace App\Services\SmartIvr\Events\Notify\Outbound;

use App\Services\SmartIvr\Notify\Outbound\BridgeResult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BridgedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notify;


    public function __construct(BridgeResult $notify)
    {
        $this->notify = $notify;
    }
}