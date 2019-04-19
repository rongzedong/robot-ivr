<?php

namespace App\Services\SmartIvr\Events\Notify\Inbound;

use App\Services\SmartIvr\Notify\Inbound\Enter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EnteringEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array 请求参数
     */
    public $notify;

    /**
     * Create a new event instance.
     *
     * @param Enter $enter
     */
    public function __construct(Enter $notify)
    {
        $this->notify = $notify;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
