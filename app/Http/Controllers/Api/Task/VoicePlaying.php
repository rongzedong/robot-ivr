<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 18:05
 */

namespace App\Http\Controllers\Api\Task;


class VoicePlaying
{
    /**
     * 播放外呼任务全程录音
     * @param string $task_id 任务ID
     * @param string $outbound_number_id 号码ID
     */
    public function outboundRecoding($task_id, $outbound_number_id)
    {
        info($task_id, $outbound_number_id);
    }
}