<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月15日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 热门关键词
* 
*/
class Hotkeywords extends AbstractServiceManager
{
    protected $table = 'Hotkeywords';
    protected $stag, $filePath;
    
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_hotkeywords";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_hotkeywords.yml";
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
        $map['order'] = 'keyword|asc';
        $info = parent::findBy($map, null, 10000);
    
        if(isset($info['data']))
        {
            $i = 0;
            foreach($info['data'] as $item)
            {
                $result[$i]['keyword'] = $item->getKeyword();
                $result[$i]['url'] = $item->getUrl();
                $i++;
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
    public function getData()
    {
        $info = $this->get('core.common')->S($this->stag);
    
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
    
            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if(empty($info))
            return array();
    
        return $info;
    }
}