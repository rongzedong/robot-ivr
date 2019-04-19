<?php

namespace App\Services\SmartIvr\Events\Notify\Inbound;

use App\Services\SmartIvr\Notify\Inbound\AsrprogressNotify;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AsrProgressedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $request;

    public $entity;

    public $bridgeStatus;

    /**
     * Create a new event instance.
     *
     * @param AsrprogressNotify $notify
     */
    public function __construct(AsrprogressNotify $notify)
    {
        $this->bridgeStatus = $notify->resolveHandle()->getBridgeStatus();
        $this->request = $notify->getReceiveData()->toArray();
        $this->entity = $notify->resolveHandle()->getHandleEntity();
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
