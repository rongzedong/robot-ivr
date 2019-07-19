<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 18:05
 */

namespace App\Http\Controllers\Api\Task;


use App\Repositories\Eloquent\OutboundCallRecordRepository;

class VoicePlaying
{
    /**
     * 播放外呼任务全程录音
     * @param OutboundCallRecordRepository $repository
     * @param string $id 呼叫ID
     * @return
     */
    public function outboundRecoding(OutboundCallRecordRepository $repository, $id)
    {

        $record_file = $repository->getRecordFile($id);

        if ($record_file) {
            return response()->download($record_file, null, [
                'Content-Type' => 'audio/x-wav'
            ], null);
        }

        abort(404, '全程语音不存在');
    }
}