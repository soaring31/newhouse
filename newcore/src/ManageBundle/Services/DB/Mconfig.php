<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月18日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 配置模型
* 
*/
class Mconfig extends AbstractServiceManager
{
    protected $stag, $filePath;
    protected $table = 'Mconfig';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_mconfig";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_mconfig.yml";
    }
 
    public function add(array $data,  $info=null, $isValid=true)
    {
        unset($data['id']);
        $data['area'] = (int)$this->get('core.area')->getArea();
        $result = parent::add($data, $info, $isValid);
        return self::handleYmlData($result);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        unset($data['id']);
        $data['area'] = (int)$this->get('core.area')->getArea();
        $result = parent::update($id, $data, $info, $isValid);
        return self::handleYmlData($result);
    }
    
    public function batchadd(array $infos)
    {
        $result = parent::batchadd($infos);
        return self::handleYmlData($result);
    }
    
    public function batchupdate(array $entities)
    {
        $result = parent::batchupdate($entities);
        return self::handleYmlData($result);
    }
    
    /**
     * 生成或重建 mconfig 的文件缓存及 memcached 缓存
     * 
     * @param multitype $data,本方法的操作与 $data 是无关的，直接基于数据库整体更新 mconfig 的缓存
     * 
     * @return:
     */     
    protected function handleYmlData($data)
    {
        if(empty($data)){
            return $data;
        }

        $result = array();
        $map = array();
        $map['area'] = '_isnull';
        $map['status'] = 1;
        $info = parent::findBy($map, null, 5000);
    
        if(isset($info['data']))
        {
            foreach($info['data'] as $item)
            {
                $result[$item->getArea()][$item->getEname()][$item->getName()]['value'] = $item->getValue();
                $result[$item->getArea()][$item->getEname()][$item->getName()]['title'] = $item->getTitle();
            }
        }

        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);
        unset($info);

        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        return $data;
    }
    
    /**
     * 读取memcache缓存或文件缓存
     * @param array $criteria
     * @return multitype:
     */
    public function getData(array $criteria)
    {
        if(!isset($criteria['ename']))
            return array();

        $criteria['area'] = isset($criteria['area'])?(int)$criteria['area']:(int)$this->get('core.area')->getArea();
        
        $info = $this->get('core.common')->S($this->stag);
        
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
            $this->get('core.common')->S($this->stag, $info, 86400);
        }
    
        if(!isset($info[$criteria['area']])||!isset($info[$criteria['area']][$criteria['ename']])||$info[$criteria['area']]==0)
            return isset($info[0][$criteria['ename']])?$info[0][$criteria['ename']]:array();

        return $info[$criteria['area']][$criteria['ename']];
    }
}