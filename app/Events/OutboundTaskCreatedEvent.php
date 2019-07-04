<?php

namespace App\Events;

use App\Models\OutboundTask;

class OutboundTaskCreatedEvent extends Event
{
    /**
     * Create a new event instance.
     *
     * @param OutboundTask $task
     */
    public function __construct(OutboundTask $task)
    {
        //
    }
}
