<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月28日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 缓存方案表
* 
*/
class Cache extends AbstractServiceManager
{
    protected $table = 'Cache';
    protected $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_cache.yml";
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        $info = parent::add($data, $info, $isValid);
        self::handleCache();
        return $info;
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $info = parent::update($id, $data, $info, $isValid);
        self::handleCache();
        return $info;
    }
    
    public function delete($id, $info=null)
    {
        $info = parent::delete($id, $info);
        self::handleCache();
        return $info;
    }
    
    public function getRule(array $criteria, $limit = 50)
    {
        $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
    
        if(isset($info['data']))
            return $info;
        else
            return parent::findBy($criteria, array(), $limit);
    
    }
    
    protected function handleCache()
    {
        $result = array();
        $map = array();
        $map['status'] = 1;
        $info = parent::findBy($map);
    
        if(isset($info['data']))
        {
            foreach($info['data'] as $key=>$item)
            {
                $result['data'][$key]['name'] = $item->getName();
                $result['data'][$key]['path'] = $item->getPath();
                $result['data'][$key]['date'] = $item->getDate();
                $result['data'][$key]['public'] = $item->getPublic();
            }
        }
         
        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);
    }
}