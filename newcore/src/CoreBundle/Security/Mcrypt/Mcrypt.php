<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月1日
*/
namespace CoreBundle\Security\Mcrypt;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Mcrypt extends ServiceBase implements McryptInterface
{
    //容器
    protected  $container;

    //盐值（某个特定的字符串）
    protected $salt;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        
        //盐值，用以提高密文的安全性
        $this->salt="~!@#$";
    }
    
    /**
     * 对明文信息进行加密
     * @param $key 密钥
     */
    public function encode($str)
    {
        //相当于动态密钥
        $key = md5($this->salt);
        $tmp = "";
        for($i=0;$i<strlen($str);$i++){
            $tmp.=substr($str,$i,1) ^ substr($key,$i,1);
        }
        return self::base_encode($tmp);
    }
    
    /**
     * 对密文进行解密
     * @param $key 密钥
     */
    public function decode($str)
    {
        $key = md5($this->salt);
        $str = self::base_decode($str);
        //$str = substr($str,3,$len-3);
        $tmp = "";
        for($i=0;$i<strlen($str);$i++){
            $tmp.=substr($str,$i,1) ^ substr($key,$i,1);
        }
        return $tmp;
    }
    
    private function _hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }

    /**
     * 取得服务器的MAC地址
     */
    public function getmac($os_type)
    {
        switch ( strtolower($os_type) )
        {
            case "linux":
                $this->forLinux();
                break;
            case "solaris":
                break;
            case "unix":
                break;
            case "aix":
                break;
            default:
                $this->forWindows();
                break;
        }

        $temp_array = array();

        foreach( $this->return_array as $value )
        {
            if (preg_match("/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i",$value,$temp_array )){
                $mac_addr = $temp_array[0];
                break;
            }
        }
        unset($temp_array);
        return $mac_addr;
    }
    /**
     * windows服务器下执行ipconfig命令
     */
    public function forWindows()
    {
        @exec("ipconfig /all", $this->return_array);
        if ( $this->return_array )
            return $this->return_array;
        else{
            $ipconfig = $_SERVER["WINDIR"]."\\system32\\ipconfig.exe";
            if ( is_file($ipconfig) )
                @exec($ipconfig." /all", $this->return_array);
            else
                @exec($_SERVER["WINDIR"]."\\system\\ipconfig.exe /all", $this->return_array);
            return $this->return_array;
        }
    }
    /**
     * Linux服务器下执行ifconfig命令
     */
    public function forLinux()
    {
        @exec("ifconfig -a", $this->return_array);
        return $this->return_array;
    }
    
    public function base_encode($str) {
        $src  = array("/","+","=");
        $dist = array("_a","_b","_c");
        $old  = base64_encode($str);
        $new  = str_replace($src,$dist,$old);
        return $new;
    }
    
    public function base_decode($str) {
        $src = array("_a","_b","_c");
        $dist  = array("/","+","=");
        $old  = str_replace($src,$dist,$str);
        $new = base64_decode($old);
        return $new;
    }
}