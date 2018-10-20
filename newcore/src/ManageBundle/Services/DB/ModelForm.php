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

class ModelForm extends AbstractServiceManager
{
    protected  $table = 'ModelForm';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_model_form";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_model_form.yml";
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
    }
    
    /**
     * 生成表单文件
     * @param int $id
     * @throws \LogicException
     */
    public function createfile($id)
    {
        if($id<=0)
            throw new \LogicException('该表单无效或已被删除');
        
        $info = self::getData($id);
        
        if(empty($info))
            throw new \LogicException('该表单无效或已被删除');

        $modelInfo = $this->get('db.models')->findOneBy(array('id'=>$info['model_id']));

        if(!is_object($modelInfo))
            throw new \LogicException('表单模型无效或已被删除');
        
        $data = array();
        $data['name'] = $info['name'];
        $data['entity'] = ucfirst($modelInfo->getName());
        $data['entityClass'] = ucfirst($info['name']);        
        $data['bundle'] = $modelInfo->getBundle()?$modelInfo->getBundle():'ManageBundle';
  
        $bundleInfo = $this->get('core.common')->getBundle($data['bundle']);
        
        $this->get('core.controller.command')->createFormType($bundleInfo, $data);
        
        return true;
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
                $result['id'][$item->getId()]['id'] = $item->getId();
                $result['id'][$item->getId()]['url'] = $item->getUrl();
                $result['id'][$item->getId()]['type'] = $item->getType();
                $result['id'][$item->getId()]['name'] = $item->getName();
                $result['id'][$item->getId()]['title'] = $item->getTitle();
                $result['id'][$item->getId()]['model_id'] = $item->getModelId();
                $result['id'][$item->getId()]['bindform'] = $item->getBindform();
                $result['id'][$item->getId()]['bindfield'] = $item->getBindfield();
                $result['id'][$item->getId()]['initmodel'] = $item->getInitmodel();
                $result['id'][$item->getId()]['identifier'] = $item->getIdentifier();
                $result['id'][$item->getId()]['parent_form'] = $item->getParentForm();                
                $result['id'][$item->getId()]['initcondition'] = $item->getInitcondition();

                $result['name'][$item->getName()]['id'] = $item->getId();
                $result['name'][$item->getName()]['url'] = $item->getUrl();
                $result['name'][$item->getName()]['type'] = $item->getType();
                $result['name'][$item->getName()]['name'] = $item->getName();
                $result['name'][$item->getName()]['title'] = $item->getTitle();
                $result['name'][$item->getName()]['model_id'] = $item->getModelId();
                $result['name'][$item->getName()]['bindform'] = $item->getBindform();
                $result['name'][$item->getName()]['bindfield'] = $item->getBindfield();
                $result['name'][$item->getName()]['initmodel'] = $item->getInitmodel();
                $result['name'][$item->getName()]['identifier'] = $item->getIdentifier();
                $result['name'][$item->getName()]['parent_form'] = $item->getParentForm();
                $result['name'][$item->getName()]['initcondition'] = $item->getInitcondition();
                
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
        
        if(is_array($str))
        {
            $result = array();

            foreach ($str as $key)
            {
                if(!isset($info[$key]))
                    continue;

                $result[$key] = $info[$key];
            }
            
            return $result;
        }
    
        return isset($info[$str])?$info[$str]:array();
    }
    
    public function getDataOrX(array $params)
    {
        $info = $this->get('core.common')->S($this->stag);
        
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
        
            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if(empty($info))
            return array();
        
        $result = array();
        foreach($params as $key=>$val)
        {
            if(!isset($info[$key][$val]))
                continue;
            
            $result[] = $info[$key][$val];
        }
        
        return $result;
    }
}