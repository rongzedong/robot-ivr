<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 18:05
 */

namespace App\Http\Controllers\Api\Task;


use App\Repositories\Eloquent\OutboundCallRecordRepository;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @param $path
     * @param $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function inboundRecoding($path, $filename)
    {
        //转换目录
        $real_path = '/recordings/' . str_replace('.', '/', $path) . '/' . $filename . '.wav';

        try {
            $storage = Storage::disk('smart_ivr');
            if ($storage->exists($real_path)) {
                return response()->download($storage->path($real_path), null, [
                    'Content-Type' => 'audio/x-wav'
                ], null);
            }
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            abort(404);
        }
    }
}