<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/8/25
 * Time: 2:36
 */

namespace App\Jobs;

use App\Models\OutboundCallRecord;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * 发送通话记录到服务端
 * Class PostOutboundCallRecordJob
 * @package App\Jobs
 */
class PostOutboundCallRecordJob extends Job
{
    public $data;

    /**
     * PostOutboundCallRecordJob constructor.
     * @param OutboundCallRecord $callRecord
     */
    public function __construct(OutboundCallRecord $callRecord)
    {
        $this->data = $callRecord->only([
                'task_id',
                'calldate',
                'duration',
                'bill',
                'hangupcause',
                'hangupdate',
                'answerdate',
                'recordfile',
                'bridge_callid',
                'bridge_number',
                'bridge_calldate',
                'bridge_answerdate',
            ]) + [
                'callid' => $callRecord->getKey()
            ];
    }

    public function handle()
    {
        try {
            $client = new Client([
                'base_uri' => 'http://59.111.104.19:18306',
                'timeout' => 2,
            ]);

            $response = $client->post('outboundRecord/sync', [
                'form_params' => $this->data,
            ]);

            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $data = $body ? json_decode($body, true) : [];

                if (Arr::get($data, 'success')) {
                    return true;
                }
            }
            throw new \RuntimeException();
        } catch (\Throwable $e) {
            $this->release(3);
        }

        //记录失败
        Log::error('发送外呼通话记录失败,数据为:', $this->data);
    }
}