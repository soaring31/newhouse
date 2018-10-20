<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月14日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthRule extends AbstractServiceManager
{
    protected $stag,$filePath;
    protected $table = 'AuthRule';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_auth_rule";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_auth_rule.yml";
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {    
        return self::handleAuthRule(parent::add($data, $info, $isValid));
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        if(isset($data['menuid'])&&$data['menuid'])
        {
            $menu = $this->get('db.menus')->findOneBy(array('id'=>(int)$data['menuid']));
            $data['pmenuid'] = is_object($menu)?$menu->getPid():0;
            $data['type'] = isset($data['pmenuid'])&&$data['pmenuid']>0?1:0;
            
            //获得原数据
            if(!is_object($info))
                $info = $this->findOneBy(array('id'=>$id), array(), false);
            
            //嵌入菜单ID
            if(method_exists($info, 'setPmenuid'))
                $info->setPmenuid(is_object($menu)?$menu->getPid():0);
            
            //嵌入type
            if(method_exists($info, 'setType'))
                $info->setType($info->getPmenuid()>0?1:0);
        }

        return self::handleAuthRule(parent::update($id, $data, $info, $isValid));
    }
    
    protected function handleAuthRule($data)
    {
        $result = array();
        $map = array();
        $map['status'] = 1;
        $info = parent::findBy($map);
    
        if(isset($info['data']))
        {
            foreach($info['data'] as $item)
            {
                $result[$item->getBundle()][$item->getController()][$item->getAction()]['id'] = $item->getId();
                $result[$item->getBundle()][$item->getController()][$item->getAction()]['type'] = $item->getType();
                $result[$item->getBundle()][$item->getController()][$item->getAction()]['menuid'] = $item->getMenuid();
                $result[$item->getBundle()][$item->getController()][$item->getAction()]['pmenuid'] = $item->getPmenuid();
            }
        }
    
        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);
    
        unset($info);
        
        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        return $data;
    }
    
    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getData(array $criteria)
    {
        if(!isset($criteria['bundle'])||!isset($criteria['controller'])||!isset($criteria['action']))
            return array();
        
        $info = $this->get('core.common')->S($this->stag);

        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
            
            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        return isset($info[$criteria['bundle']][$criteria['controller']][$criteria['action']])?$info[$criteria['bundle']][$criteria['controller']][$criteria['action']]:array();
    }
}