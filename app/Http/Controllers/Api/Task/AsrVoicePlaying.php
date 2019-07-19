<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/19
 * Time: 18:33
 */

namespace App\Http\Controllers\Api\Task;


use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * 通话asr 语音播放
 * Class AsrVoicePlaying
 * @package App\Http\Controllers\Api\Task
 */
class AsrVoicePlaying extends Controller
{
    /**
     * @param string $path 目录 层级以.分割
     * @param string $filename 文件名
     * @return string
     */
    public function index($path, $filename)
    {
        //转换目录
        $real_path = str_replace('.', '/', $path) . '/' . $filename . '.wav';

        try {
            $storage = Storage::disk('smart_ivr');
            dd($real_path, $storage->exists($real_path));
            if ($storage->exists($real_path)) {
                return $storage->get($real_path);

            }
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            abort(404);
        }

    }

}