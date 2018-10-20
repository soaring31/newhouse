<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月22日
*/
namespace WeixinBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 微信默认设置
*
*/
class Wxdefault extends AbstractServiceManager
{
    protected $table = 'Wxdefault';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function defaultKey($key, $token)
    {
    	if(!empty($token) && !empty($key))
    	{
            $map = array();
            $map['token'] = $token;
            $map['findType'] = 1;
    		$defaults = parent::findOneBy($map);
    	}
    	return isset($defaults[$key])?$defaults[$key]:'没有设置默认回复！';
    }

    public function transmitKey($keyword, $token)
    {
    	if(!empty($token) && !empty($keyword)){
    		$defaults = parent::findOneBy(array('token'=>$token));
            if($defaults){
        		foreach(explode("，", $defaults->getTransmit()) as $item){
        			if(strpos($keyword, $item) !== false)
        				return true;
        		}
            }
    	}
    	return false;
    }

    public function robotSwitch($keyword, $token)
    {
    	return false; //聊天机器人接口入口
    }

}