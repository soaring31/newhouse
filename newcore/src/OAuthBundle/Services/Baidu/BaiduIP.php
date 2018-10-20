<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2015年12月2日
 */

namespace OAuthBundle\Services\Baidu;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaiduIP extends ServiceBase
{

    protected $container;
    protected $apiUrl = 'http://api.map.baidu.com/location/ip';
    protected $addressUrl = 'http://api.map.baidu.com/geocoder/v2/?address=%s&output=json&ak=%s';
    protected $name = 'baidumap';

    public function __construct(ContainerInterface $container)
    {
        $this->container     = $container;
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner($this->name);
        $errorCode           = new ErrorCode();
        $this->errorCode     = $errorCode->getText();
        $this->access_key    = $this->resourceOwner->getOption('client_secret');
        $this->geotable_id   = $this->resourceOwner->getOption('client_id');
    }

    public function getIps()
    {
        return $this->get('request')->getClientIps();
    }

    public function getIp()
    {
        $ip = $this->get('request')->getClientIp();

        //如果是内网IP 则默认为东莞IP
//	    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)?$ip:"121.13.197.115";
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ? $ip : "123.125.71.38";
    }


    /**
     * 根据地址获取国家、省份、城市及周边数据
     * @param  String $ip ip地址
     * @return Array
     */
    public function getAddress($ip = '')
    {
        //其实可为空：ip不出现，或者出现且为空字符串的情况下，百度会自动使用当前访问者的IP地址作为定位参数.
        $input        = array();
        $input['ak']  = $this->access_key;
        $input['ip']  = $ip ? $ip : $this->getIp();
        $input['ips'] = $ip ? $ip : $this->getIp();

        // 在调试帐号时需要清缓存，才能更新定位结果
        $address = $this->get('core.common')->S(md5(json_encode($input)));
        if (!$address) {
            $address = $this->resourceOwner->doGetRequest($this->apiUrl, $input, false);
            $this->get('core.common')->S(md5(json_encode($input)), $address, 86400);
        }

        if ($address->status !== 0) {
            $address = array();//如果定位不成功，保持静默
            //throw new \LogicException('Ip定位错误：'.$address->message);
        }

        return $address;
    }
}