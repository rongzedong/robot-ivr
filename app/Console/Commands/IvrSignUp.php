<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/24
 * Time: 18:13
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use XsKit\PassportClient\Facades\PassportClient;

class IvrSignUp extends Command
{

    protected $signature = 'ivr:signup';

    protected $description = '注册 ivr 节点到服务端';

    public function handle()
    {
        $accessToken = $this->getAccessToken();

        if (empty($accessToken)) {
            $this->error('获取授权失败');
            return;
        }

        $name = $this->ask('请输入你的名称');

        $description = $this->ask('请输入一段描述文字');

        $total_concurrency_quota = $this->ask('请输入 你的 IVR 呼叫限制的最大并发数');

        $app_host = $this->getAppHost();

        $response = PassportClient::request()
            ->token($accessToken)
            ->query('/api/ivr-node/sign-up')
            ->param([
                'name' => $name,
                'description' => $description,
                'total_concurrency_quota' => $total_concurrency_quota,
                'app_host' => $app_host,
            ])->post();

        if ($response->isErr() || empty(Arr::get($response, 'app_key'))) {
            $this->error('获取信息失败，请重试多试几次 或 联系服务供应商解决。');
            return;
        }

        $this->comment('注册成功。');

        $this->info('名称：' . Arr::get($response, 'name'));
        $this->info('描述：' . Arr::get($response, 'description'));
        $this->info('IVR主机(Host):' . Arr::get($response, 'app_host'));
        $this->info('最大并发限制数:' . Arr::get($response, 'total_concurrency_quota'));
        $this->info('IVR_KEY:' . Arr::get($response, 'app_key'));
        $this->info('IVR_SECRET:' . Arr::get($response, 'app_secret'));

        $this->line('把获取到的 IVR_KEY 和 IVR_SECRET 信息配置到 .env 对应的位置上。');


    }


    private function getAccessToken($anticipates = [])
    {
        $username = $this->anticipate('请输入用户名（邮箱）', $anticipates);

        $password = $this->secret('请输入密码');

        $token = PassportClient::grantPassword()->signIn($username, $password)->accessToken();

        if ($token->isErr()) {

            $this->error('用户名或密码 验证不通过。');

            return $this->getAccessToken([$username]);
        }

        return Arr::get($token, 'access_token');
    }

    private function getAppHost()
    {
        $app_host = $this->ask('请输入 IVR主机(host)，请以 http:// 或 https:// 开头');

        if (!Str::startsWith($app_host, ['http://', 'https://'])) {
            $this->error('IVR主机(host) 格式错误。');
            $app_host = $this->getAppHost();
        }

        return $app_host;

    }
}
