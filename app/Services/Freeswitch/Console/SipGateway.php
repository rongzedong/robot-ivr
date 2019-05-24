<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/4/18
 * Time: 15:27
 */

namespace App\Services\Freeswitch\Console;


class SipGateway
{
    /**
     * 生成 sip 配置文件
     * @param $file
     * @param $gateway
     * @param $realm
     * @param $username
     * @param string $password
     * @param string $form_domain
     * @return bool
     */
    public static function make($file, $gateway, $realm, $username, $password = '', $form_domain = '')
    {
        $content = <<<eof
<include>
  <gateway name="$gateway">
  <param name="from-user" value="$username"/>
  <param name="username" value="$username"/>
  <param name="realm" value="$realm"/>
  <param name="password" value="$password"/>
  <param name="extension" value="$username"/>
  <param name="expire-seconds" value="60"/>
    <param name="from-domain" value="$form_domain"/>
  <param name="register" value="true"/>
  <param name="caller-id-in-from" value="true"/>
  <param name="extension-in-contact" value="true"/>
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
