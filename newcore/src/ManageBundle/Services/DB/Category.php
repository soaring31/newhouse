<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月30日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 栏目分类
* 
*/
class Category extends AbstractServiceManager
{
    protected $table = 'Category';
    protected $stag, $stag1, $filePath, $filePath1;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_category";        
        $this->stag1 = $this->get('core.common')->C('database_name')."_categorybyid";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_category.yml";
        $this->filePath1 = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_categorybyid.yml";
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
        $result1 = array();
        $map = array();
        $map['status'] = 1;
        $map['checked'] = 1;
        $map['order'] = 'sort|asc,id|asc';
        $info = parent::findBy($map);
    
        if(isset($info['data']))
        {
            foreach($info['data'] as $item)
            {
                $result[$item->getEname()][$item->getId()]['id'] = $item->getId();
                $result[$item->getEname()][$item->getId()]['name'] = $item->getName();
                $result[$item->getEname()][$item->getId()]['pid'] = $item->getPid();
                $result[$item->getEname()][$item->getId()]['ename'] = $item->getEname();
                $result[$item->getEname()][$item->getId()]['models'] = $item->getModels();
                $result[$item->getEname()][$item->getId()]['keywords'] = $item->getKeywords();
                $result[$item->getEname()][$item->getId()]['description'] = $item->getDescription();
                $result[$item->getEname()][$item->getId()]['cate_action'] = $item->getCateAction();
                $result[$item->getEname()][$item->getId()]['remark'] = $item->getRemark();
                $result[$item->getEname()][$item->getId()]['sqlstr'] = $item->getSqlstr();
                $result[$item->getEname()][$item->getId()]['is_next'] = $item->getIsNext();
                $result[$item->getEname()][$item->getId()]['form_id'] = $item->getFormId();
                $result[$item->getEname()][$item->getId()]['checked'] = $item->getChecked();
                
                $result1[$item->getId()]['id'] = $item->getId();
                $result1[$item->getId()]['name'] = $item->getName();
                $result1[$item->getId()]['pid'] = $item->getPid();
                $result1[$item->getId()]['ename'] = $item->getEname();
                $result1[$item->getId()]['models'] = $item->getModels();
                $result1[$item->getId()]['keywords'] = $item->getKeywords();
                $result1[$item->getId()]['description'] = $item->getDescription();
                $result1[$item->getId()]['cate_action'] = $item->getCateAction();
                $result1[$item->getId()]['remark'] = $item->getRemark();
                $result1[$item->getId()]['sqlstr'] = $item->getSqlstr();
                $result1[$item->getId()]['is_next'] = $item->getIsNext();
                $result1[$item->getId()]['form_id'] = $item->getFormId();
                $result1[$item->getId()]['checked'] = $item->getChecked();
            }
        }
    
        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);
        $this->get('core.ymlParser')->ymlWrite($result1, $this->filePath1);

        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        $this->get('core.common')->S($this->stag1, $result1, 86400);
        
        unset($info);
        unset($result);
        unset($result1);
        return $data;
    }
    
    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getData(array $criteria)
    {    
        $info = $this->get('core.common')->S($this->stag);
    
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
    
            $this->get('core.common')->S($this->stag, $info, 86400);
        }
        
        if(empty($info)||!isset($criteria['ename']))
            return array();
        
        
        return isset($info[$criteria['ename']])?$info[$criteria['ename']]:array();
    
    }
    
    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getDataById(array $criteria)
    {
        $info = $this->get('core.common')->S($this->stag1);
    
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath1);
    
            $this->get('core.common')->S($this->stag1, $info, 86400);
        }
    
        if(empty($info)||!isset($criteria['id']))
            return array();
    
    
        return isset($info[$criteria['id']])?$info[$criteria['id']]:array();
    
    }
}