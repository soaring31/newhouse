<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年04月21日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 认证防火墙
* 
*/
class AuthFirewall extends AbstractServiceManager
{
    protected $stag,$filePath;
    protected $table = 'AuthFirewall';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_auth_firewall";

        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_firewall.yml";
    }

    public function add(array $data,  $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }

    public function update($id, array $data, $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
    }

    public function getRule(array $criteria, $limit = 50)
    {        
        $info = $this->get('core.common')->S($this->stag);
        
        if(empty($info))
        {
            $this->get('core.common')->S($this->stag, $info, 86400);
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
        }
        
        if(isset($info['data']))
            return $info;
        else
            return parent::findBy($criteria, array(), $limit);
    }

    protected function handleYmlData($data)
    {
        $result = array();
        $map = array();
        $map['status'] = 1;
        $info = parent::findBy($map);
        
        if(!isset($info['data']))
            return $data;
        
        foreach($info['data'] as $key=>$item)
        {
            $result['data'][$key]['name'] = $item->getName();
            $result['data'][$key]['path'] = $item->getPath();
            $result['data'][$key]['ips'] = $item->getIps();
        }

        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);
        
        //清除缓存
        $this->get('core.common')->S($this->stag, null);
        
        return $data;
    }
}