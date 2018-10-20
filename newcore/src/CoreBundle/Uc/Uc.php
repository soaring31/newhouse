<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace CoreBundle\Uc;

use CoreBundle\Security\User\User;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * LdapUserManager manages LDAP users and roles.
 *
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
define('UC_CONNECT', 'mysql');

define('UC_DBHOST', 'localhost');
define('UC_DBUSER', 'root');
define('UC_DBPW', '123456');
define('UC_DBNAME', 'ultrax');
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`ultrax`.pre_ucenter_');
define('UC_DBCONNECT', 0);

define('UC_CHARSET', 'utf-8');
define('UC_KEY', 'Oe63053ft74735a4L9c4R1N1wdwf5cF180dfaeMat1d9n8bbq0ucPcU2vagb4dse');
define('UC_API', 'http://localhost/discuz/uc_server');
define('UC_APPID', '1');
define('UC_IP', '');
define('UC_PPP', 20);
define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');

class Uc extends ServiceBase
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function check()
    {        
        $get = $post = array();
        
        $code = $this->get('request')->get('code','');

        parse_str(self::authcode($code, 'DECODE', UC_KEY), $get);

        
        if(time() - $get['time'] > 3600) {
            exit('Authracation has expiried');
        }
        if(empty($get)) {
            exit('Invalid Request');
        }

        if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed'))) {
            
            $matchesStr = array($get, $post);
            
            call_user_func_array("self::{$get['action']}",$matchesStr);
            exit(API_RETURN_SUCCEED);
        } else {
            exit(API_RETURN_FAILED);
        }
    }
    
    protected function test()
    {
        exit(API_RETURN_SUCCEED);
    }
    
    /**
     * 登入
     * @param array $get
     * @param array $post
     */
    protected function synlogin(array $get, array $post)
    {
        //根据UID取用户信息
        $user = $this->get('db.users')->findOneBy(array('id'=>$get['uid']));
        
        if(!is_object($user))
            exit(API_RETURN_FAILED);

        //登陆
        $this->get('core.rbac')->loginHandle(new User($user));

        exit(API_RETURN_SUCCEED);
    }
    
    /**
     * 登出
     * @param array $get
     * @param array $post
     */
    protected function synlogout(array $get, array $post)
    {
        exit(API_RETURN_SUCCEED);
    }
    
    public function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    
        $ckey_length = 4;
    
        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
    
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
    
        $result = '';
        $box = range(0, 255);
    
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
    
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
    
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
    
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    
    }
}
