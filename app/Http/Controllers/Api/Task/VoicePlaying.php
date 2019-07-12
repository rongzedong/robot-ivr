<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 18:05
 */

namespace App\Http\Controllers\Api\Task;


use App\Repositories\Eloquent\OutboundNumberRepository;

class VoicePlaying
{
    /**
     * 播放外呼任务全程录音
     * @param OutboundNumberRepository $repository
     * @param string $task_id 任务ID
     * @param string $outbound_number_id 号码ID
     * @return
     */
    public function outboundRecoding(OutboundNumberRepository $repository, $task_id, $outbound_number_id)
    {

        $outboundNumber = $repository->setTask($task_id)->find($outbound_number_id);

        if ($outboundNumber) {
            return response()->file($outboundNumber->recordfile, [
                'Content-Type' => 'audio/x-wav'
            ]);
        }

        abort(404, '全程语音不存在');
    }
}