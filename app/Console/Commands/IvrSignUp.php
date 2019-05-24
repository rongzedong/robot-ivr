<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/24
 * Time: 18:13
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;

class IvrSignUp extends Command
{

    protected $signature = 'ivr:signup';

    protected $description = '注册 ivr 节点到服务端';

    public function handle()
    {
        $name = $this->ask('请输入你的名称：');

        $description = $this->ask('请输入一段描述文字：');

        $total_concurrency_quota = $this->ask('请输入你的 IVR 呼叫限制的最大并发数：');

        $this->line($name . ',' . $description . ',' . $total_concurrency_quota);
    }

}
