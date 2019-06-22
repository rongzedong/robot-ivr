<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/6/22
 * Time: 16:54
 */

namespace App\Services\Freeswitch\Console;


use Illuminate\Support\Facades\Log;

class ConsoleDoShell
{
    /**
     * 执行shell命令
     *
     * @param $cmd
     * @param null $cwd
     * @return array|bool
     */
    protected function doShell($cmd, $cwd = null)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"), // stdin
            1 => array("pipe", "w"), // stdout
            2 => array("pipe", "w"), // stderr
        );
        $process = proc_open($cmd, $descriptorspec, $pipes, $cwd, null);

        // $process为false，表明命令执行失败
        if ($process == false) {
            Log::error('命令执行出错');
            return false;
        }

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $status = proc_close($process); // 释放process

        return array(
            'stdout' => $stdout, // 标准输出
            'stderr' => $stderr, // 错误输出
            'return_var' => $status, // 返回进程的终止状态码。如果发生错误，将返回 -1
        );

    }
}