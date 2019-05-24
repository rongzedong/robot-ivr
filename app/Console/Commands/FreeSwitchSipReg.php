<?php

namespace App\Console\Commands;

use App\Services\Freeswitch\Console\FsCli;
use App\Services\Freeswitch\Console\SipGateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use \RuntimeException;

class FreeSwitchSipReg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fs:sip_reg 
                            {gateway : 网关名}
                            {realm : 地址} 
                            {username : 用户名} 
                            {password? : 密码}
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
        $username = $this->argument('username');
        $password = $this->argument('password') ?? '';

        $from_domain = $this->argument('from-domain') ?? '';

        $this->info('开始注册 FreeSwitch SIP 网关...');
        $bar = $this->output->createProgressBar(4);

        try {
            $dir = env('FREESWITCH_SIP_DIR');
            //生成配置文件
            $this->mkdir($dir);
            $file = $dir . $gateway . '.xml';
            $this->mkfile($file, $gateway, $this->argument('realm'), $username, $password, $from_domain);

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

            $this->fsCli->reloadxml()->sofia($gateway)->sofiaExternalKillGw()->sofiaExternalRescan()->sofiaExternalRegister();

            //检查注册结果
            $bar->advance();
            $this->info('检查注册状态...');

            if ($this->checkReged($gateway)) {
                $bar->finish();
                $this->info('结果：已注册');

            } else {
                $bar->finish();
                $this->info('结果：未注册');
            }
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
    }

    private function mkfile($file, $gateway, $reaml, $username, $password, $from_domain)
    {
        if (!SipGateway::make($file, $gateway, $reaml, $username, $password, $from_domain)) {
            $error_msg = "文件 [ $file ] 配置失败";
            $this->error($error_msg);
            throw new RuntimeException($error_msg);
        }

    }

    private function checkReged($gateway)
    {
        return array_search($gateway, $this->fsCli->sofiaExternalGwList()->output);
    }
}
