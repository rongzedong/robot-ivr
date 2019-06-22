<?php

namespace App\Console\Commands;

use App\Services\Freeswitch\Console\FsCli;
use App\Services\Freeswitch\Console\IpGateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use \RuntimeException;

class FreeSwitchIpReg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fs:ip_reg 
                            {gateway : 网关名}
                            {realm : 地址} 
                            {proxy? : 代理地址}
                            {from-domain? : 域名}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '注册 FreeSwitch SIP 网关信息';

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
        $realm = $this->argument('realm');
        $proxy = $this->argument('proxy') ?: $realm;

        $from_domain = $this->argument('from-domain') ?? '';

        $this->info('开始注册 FreeSwitch SIP 网关...');
        $bar = $this->output->createProgressBar(4);

        try {
            $dir = config('common.freeswitch_sip_dir');
            //生成配置文件
            $file = $this->mkdir($dir) . $gateway . '.xml';
            $this->mkfile($file, $gateway, $realm, $proxy, $from_domain);

            $bar->advance();
            $this->info("文件 [ $file ] 配置成功");

            //注册
            if ($this->checkReged($gateway)) {
                $bar->finish();
                $this->info('已注册');
                return;
            }
            $bar->advance();
            $this->info('注册...');

            $this->fsCli->reloadxml()->sofia($gateway)->sofiaExternalKillGw()->sofiaExternalRescan();
            //查注册结果
            $bar->advance();
            $this->info('检查注册状态...');
            sleep(3);

            if ($this->checkReged($gateway)) {
                $bar->finish();
                $result = '结果：已注册';
                $this->info($result);
            } else {
                $bar->finish();
                $result = '结果：未注册';
                $this->info($result);
            }

            Log::info('线路IP注册', [
                'gateway' => $gateway,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            $this->error('结果：注册SIP网关失败,原因:' . $e->getMessage());
            Log::error('结果：注册SIP网关失败,原因:' . $e->getMessage(), [
                'gateway' => $gateway,
            ]);
        }
    }

    private function mkdir($path)
    {
        if (is_dir($path) ? false : mkdir($path, 0777, true)) {
            $error_msg = "文件夹[ {$path} ]不存在";
            $this->error($error_msg);
            throw new RuntimeException($error_msg);
        }
        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    private function mkfile($file, $gateway, $realm, $proxy, $from_domain)
    {
        if (!IpGateway::make($file, $gateway, $realm, $proxy, $from_domain)) {
            $error_msg = "文件 [ $file ] 配置失败";
            $this->error($error_msg);
            throw new RuntimeException($error_msg);
        }

    }

    private function checkReged($gateway)
    {
        return false !== array_search($gateway, $this->fsCli->sofiaExternalGwList()->output);
    }
}
