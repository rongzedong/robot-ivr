<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/4/18
 * Time: 14:42
 */

namespace App\Services\SmartIvr\Console;


use RuntimeException;

class FsCli
{
    /**
     * @var array
     */
    public $output = [];

    /**@var string */
    private $gateway = 'all';

    public function reloadxml()
    {
        return $this->afterHandle('fs_cli -x reloadxml', '重新加载配置文件失败');
    }


    public function sofiaExternalGwList()
    {
        return $this->afterHandle("fs_cli -x 'sofia profile external gwlist'", '获取网关可用列表失败');
    }

    public function sofia($gateway = 'all')
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function sofiaExternalRescan()
    {
        return $this->afterHandle("fs_cli -x 'sofia profile external rescan {$this->gateway}'", '读取网关 [' . $this->gateway . '] 失败');
    }

    public function sofiaExternalRegister()
    {
        return $this->afterHandle("fs_cli -x 'sofia profile external register {$this->gateway}'", '注册网关 [' . $this->gateway . '] 失败');
    }

    public function sofiaExternalUnRegister()
    {
        return $this->afterHandle("fs_cli -x 'sofia profile external unregister {$this->gateway}'", '注销网关 [' . $this->gateway . '] 失败');
    }

    public function sofiaExternalKillGw()
    {
        return $this->afterHandle("fs_cli -x 'sofia profile external killgw {$this->gateway}'", '删除网关 [' . $this->gateway . '] 失败');
    }

    public function sofiaStatus($gateway = null)
    {
        return $this->afterHandle(is_null($gateway) ?
            "fs_cli -x 'sofia status'" :
            "fs_cli -x 'sofia status' | grep {$gateway}", '查看网关注册状态失败');
    }


    private function afterHandle($cmd, $err_message = '')
    {
        exec($cmd, $output, $return_var);

        foreach ($output as $value) {
            $item = preg_split('/\s+/', $value);
            if (is_array($item)) {
                $this->output = array_merge($this->output, $item);
            }
        }

        if ($return_var !== 0) {
            throw new RuntimeException($err_message, $return_var);
        }
        return $this;
    }


}