<?php

namespace App\Jobs\Amqp\Crm;

use App\Jobs\Job;


class CallerLineGoIPRegisterJob extends Job
{
    public $data;

    public function handle()
    {
        info('line register:', $this->data);
    }
}
