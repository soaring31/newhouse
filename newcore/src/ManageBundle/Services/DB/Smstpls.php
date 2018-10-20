<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月08日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 短信模版
* 
*/
class Smstpls extends AbstractServiceManager
{
    protected $table = 'Smstpls';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_smstpls";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_smstpls.yml";
    }
    
    /**
     * 添加
     * @param array $data
     * @return int id
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }
    
    /**
     * 更新
     * @param int	$id		基于ID的更新条件
     * @param array $data	更新的参数
     * @return bool
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
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
                $result['id'][$item->getId()]['tid'] = $item->getTid();
                $result['id'][$item->getId()]['name'] = $item->getName();
                $result['id'][$item->getId()]['tpl'] = $item->getTpl();
                $result['id'][$item->getId()]['checked'] = $item->getChecked();
    
                $result['tid'][$item->getTid()]['tid'] = $item->getTid();
                $result['tid'][$item->getTid()]['name'] = $item->getName();
                $result['tid'][$item->getTid()]['tpl'] = $item->getTpl();
                $result['tid'][$item->getTid()]['checked'] = $item->getChecked();
                
                $result['ename'][$item->getEname()]['tid'] = $item->getTid();
                $result['ename'][$item->getEname()]['name'] = $item->getName();
                $result['ename'][$item->getEname()]['tpl'] = $item->getTpl();
                $result['ename'][$item->getEname()]['checked'] = $item->getChecked();
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
            case 'tid':
                $info = isset($info['tid'])?$info['tid']:array();
                break;
            case 'ename':
                $info = isset($info['ename'])?$info['ename']:array();
                break;
            default:
                $info = isset($info['id'])?$info['id']:array();
                break;
        }

        return isset($info[$str])?$info[$str]:array();
    }
}