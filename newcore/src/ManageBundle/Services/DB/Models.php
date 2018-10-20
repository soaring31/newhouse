<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月18日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Models extends AbstractServiceManager
{
    protected $table = 'Models';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_models";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_models.yml";
    }
    
    /**
     * 添加
     * @param array $data
     * @return int id
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        if(isset($data['name'])&&!preg_match("/^[a-z_]+$/",$data['name']))
            throw new \LogicException('模型名称只能是英文字母');
        
        if(isset($data['service_name'])&&!preg_match("/^[a-z._]+$/",$data['service_name']))
            throw new \LogicException('服务名称只能是英文字母');

        $data['engine_type'] = isset($data['engine_type'])?$data['engine_type']:"MyISAM";
        
        if(isset($data['bundle'])&&$data['bundle'])
            $this->get('core.controller.command')->createService($data['bundle'], $data);
        return self::handleYmlData(parent::add($data, $info, $isValid));
        //return parent::add($data, $info, $isValid);
    }
    
    /**
     * 添加
     * @param array $data
     * @return int id
     */
    public function add1(array $data, $info=null)
    {       
        $data['engine_type'] = isset($data['engine_type'])?$data['engine_type']:"MyISAM";
        
        if(isset($data['bundle'])&&$data['bundle'])
            $this->get('core.controller.command')->createService($data['bundle'], $data);
        return self::handleYmlData(parent::add($data, $info, false));
    }
    
    /**
     * 更新
     * @param int	$id		基于ID的更新条件
     * @param array $data	更新的参数
     * @return bool
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {

        if(isset($data['name'])&&!preg_match("/^[a-z_]+$/",$data['name']))
            throw new \LogicException('模型名称只能是英文字母');
        
        if(isset($data['service_name'])&&!preg_match("/^[a-z._]+$/",$data['service_name']))
            throw new \LogicException('服务名称只能是英文字母');

         //$result = parent::update($id, $data, $info);
         $result = self::handleYmlData(parent::update($id, $data, $info, $isValid));

         if(isset($data['name']))
         {
             if(isset($data['bundle'])&&$data['bundle'])
                 $this->get('core.controller.command')->createService($data['bundle'], $data);
         
             if(isset($data['associated'])&&is_array($data['associated']))
             {
                 foreach($data['associated'] as $item)
                 {
                     $map = array();
                     $map['model_id'] = $item;
                     $map['name'] = $data['name'];
                     $count = $this->get('db.model_attribute')->count($map);
         
                     if($count==0)
                     {
                         $map['title'] = $data['title']."id";
                         $map['type'] = 'numeric';
         
                         $this->get('db.model_attribute')->add($map);
         
                         $this->get('db.model_attribute')->createDbTable($item);
                     }
                 }
             }
         }
         
         return $result;
    }
    
    public function handleYmlData($data)
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
                $result['id'][$item->getId()]['type'] = $item->getType();
                $result['id'][$item->getId()]['mode'] = $item->getMode();                
                $result['id'][$item->getId()]['bundle'] = $item->getBundle();
                $result['id'][$item->getId()]['service'] = $item->getServiceName();
                $result['id'][$item->getId()]['structure'] = $item->getStructure();
                $result['id'][$item->getId()]['identifier'] = $item->getIdentifier();                
                $result['id'][$item->getId()]['attributeTable'] = $item->getAttributeTable();

                $result['name'][$item->getName()]['type'] = $item->getType();
                $result['name'][$item->getName()]['mode'] = $item->getMode();
                $result['name'][$item->getName()]['bundle'] = $item->getBundle();
                $result['name'][$item->getName()]['service'] = $item->getServiceName();
                $result['name'][$item->getName()]['structure'] = $item->getStructure();
                $result['name'][$item->getName()]['identifier'] = $item->getIdentifier();
                $result['name'][$item->getName()]['attributeTable'] = $item->getAttributeTable();
                
                $result['identifier'][$item->getIdentifier()]['id'] = $item->getId();
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
    public function getData($str, $flag=null)
    {    
        $info = $this->get('core.common')->S($this->stag);
    
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
    
            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if(empty($info))
            return array();

        switch($flag)
        {
            case 'name':
                $info = isset($info['name'])?$info['name']:array();
                break;
            case 'identifier':
                $info = isset($info['identifier'])?$info['identifier']:array();
                break;
            default:
                $info = isset($info['id'])?$info['id']:array();
                break;
        }

        return isset($info[$str])?$info[$str]:array();
    }
}