<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/4/18
 * Time: 15:27
 */

namespace App\Services\Freeswitch\Console;


class IpGateway
{
    /**
     * 生成 sip 配置文件
     * @param $file
     * @param $gateway
     * @param string $realm
     * @param string $proxy
     * @param string $from_domain
     * @return bool
     */
    public static function make($file, $gateway, $realm = '', $proxy = '', $from_domain = '')
    {
        $content = <<<eof
<include>
    <gateway name="$gateway">
	  <param name="proxy" value="$proxy"/>
	  <param name="realm" value="$realm"/>
	  <param name="from-domain" value="$from_domain"/>
	  <param name="register" value="false"/>
	  <param name="rtp-autofix-timing" value="false"/>
	  <param name="caller-id-in-from" value="true"/>
	  <param name="ping" value="25"/>
	</gateway>
</include>
eof;

        if (!file_put_contents($file, $content)) {
            return false;
        }
        return true;
    }

    public static function destroy($file)
    {
        return file_exists($file) ? @unlink($file) : true;
    }
}
