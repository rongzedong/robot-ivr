<?php

namespace App\Services\SmartIvr\Events\Notify\Outbound;

use App\Services\SmartIvr\Notify\Outbound\AsrmessageNotify;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AsrMessagingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notify;

    /**
     * Create a new event instance.
     *
     * @param AsrmessageNotify $notify
     */
    public function __construct(AsrmessageNotify $notify)
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
