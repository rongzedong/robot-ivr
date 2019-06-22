<?php

namespace App\Jobs\Amqp\Client;

use App\Jobs\Job;
use App\Services\Freeswitch\Console\FsCli;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;


class CallerLineIPRegisterJob extends Job
{
    public $data;

    public function handle()
    {


        $gateway = Arr::get($this->data, 'dial_str.gateway');
        $result = Artisan::call('fs:ip_reg', [
            'gateway' => $gateway,
            'realm' => Arr::get($this->data, 'dial_str.realm'),
            'from-domain' => Arr::get($this->data, 'dial_str.from_domain'),
        ]);
        $i = 1;
        do {
            sleep(1);
            $is_success = array_search($gateway, app(FsCli::class)->sofiaExternalGwList()->output);
            $i++;
        } while (!$is_success && $i < 10);

        //记录注册结果
        Log::info('FreeSwitch Sip Gateway register:', [
            'gateway' => $gateway,
            'result' => $result === 0 && false !== $is_success
        ]);

    }


    public function failed($exception = null)
    {
        info('SIP网关注册失败 ' . optional($exception)->getMessage());
    }
}