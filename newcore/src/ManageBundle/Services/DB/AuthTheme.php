<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月10日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 非强行切换主题页面
* 
*/
class AuthTheme extends AbstractServiceManager
{
    protected $table = 'AuthTheme';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_auth_theme";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_auth_theme.yml";
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
    }
    
    protected function handleYmlData($data)
    {
        $result = array();
        $map = array();
        $map['status'] = 1;
        $map['order'] = 'sort|asc,id|asc';
        $info = parent::findBy($map, null, 10000);
    
        if(isset($info['data']))
        {
            foreach($info['data'] as $item)
            {
                $result[$item->getBundle()][$item->getController()][$item->getAction()] = 1;
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
    public function getData(array $params)
    {
        $info = $this->get('core.common')->S($this->stag);
    
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
    
            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if(empty($info)||!isset($params['bundle'])||!isset($info[$params['bundle']]))
            return false;
        
        if(!isset($params['controller']))
            return $info[$params['bundle']];
        
        if(!isset($info[$params['bundle']][$params['controller']]))
            return false;
        
        if(!isset($params['action']))
            return $info[$params['bundle']][$params['controller']];
        
        if(!isset($info[$params['bundle']][$params['controller']][$params['action']]))
            return false;
        
        return true;
    }
}