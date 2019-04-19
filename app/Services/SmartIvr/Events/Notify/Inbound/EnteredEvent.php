<?php

namespace App\Services\SmartIvr\Events\Notify\Inbound;

use App\Services\SmartIvr\Contracts\PayloadContract;
use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use App\Services\SmartIvr\Notify\Inbound\Enter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EnteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var HandleEntity $entity
     */
    public $entity;
    /**
     * 请求参数
     * @var array $request
     */
    public $request;

    /**
     * 话术负载
     * @var PayloadContract $payload
     */
    public $payload;

    /**
     * @var \App\Services\SmartIvr\Contracts\ModelContract|null $model
     */
    public $model;


    /**
     * Create a new event instance.
     *
     * @param Enter $notify
     */
    public function __construct(Enter $notify)
    {
        $this->request = $notify->getReceiveData()->toArray();
        $this->entity = $notify->resolveHandle()->getHandleEntity();
        $this->payload = $notify->getPayload();
        $this->model = $this->payload->getModel();
    }

}
