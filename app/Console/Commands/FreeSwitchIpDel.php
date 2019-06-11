<?php

namespace App\Console\Commands;

use App\Services\Freeswitch\Console\FsCli;
use App\Services\Freeswitch\Console\IpGateway;
use Illuminate\Console\Command;

class FreeSwitchIpDel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fs:ip_del 
                            {gateway : 网关名}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除 FreeSwitch IP 网关信息';

    /**@var FsCli */
    private $fsCli;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param FsCli $fsCli
     * @return mixed
     */
    public function handle(FsCli $fsCli)
    {
        $this->fsCli = $fsCli;

        $gateway = $this->argument('gateway');


        $dir = config('common.freeswitch_sip_dir');

        $this->info('开始删除 FreeSwitch IP 网关...');

        try {
            //注销
            $this->fsCli->sofia($gateway)->sofiaExternalKillGw();

            //删除配置xml文件
            $file = $dir . $gateway . '.xml';
            if (!IpGateway::destroy($file)) {
                $this->error("文件[ $file ]删除失败");
                return;
            }

            $this->info("结果：删除成功");

        } catch (\Exception $e) {
            $this->error('结果：删除IP网关失败,原因:' . $e->getMessage());
        }
    }


}
