<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月13日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 监听模型
* 
*/
class EventListen extends AbstractServiceManager
{
    protected $stag, $filePath;
    protected $table = 'EventListen';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_event_listen";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_event_listen.yml";
    }

    public function add(array $data,  $info=null, $isValid=true)
    {
        $data = $this->_getPath($data);
        return self::handleYmlData(parent::add($data, $info, $isValid));

    }

	public function update($id, array $data, $info=null, $isValid=true)
	{
	    $data = $data?$this->_getPath($data):array();
		return self::handleYmlData(parent::update($id, $data, $info, $isValid));
	}

	// 自动添加模型路径
	private function _getPath($data)
	{
		$arr = array('model','bind');
		foreach ($arr as $key)
		{
			$modid = $data["{$key}_name"];
			if(empty($modid)) continue;
			$objk = "{$key}_path";
			$rmod = $this->get('db.models')->findOneBy(array('name'=>$modid));
			if(!empty($rmod)) 
				$data[$objk] = $rmod->getServicename();
			else
				$data[$objk] = ''; // 清空
		}
		return $data;
	}
	
	protected function handleYmlData($data)
	{
	    $result = array();
	    $map = array();
	    $map['status'] = 1;
	    $info = parent::findBy($map);

	    if(isset($info['data']))
	    {
	        foreach($info['data'] as $item)
	        {
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['op'] = $item->getOp();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['model_name'] = $item->getModelName();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['model_path'] = $item->getModelPath();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['bind_name'] = $item->getBindName();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['bind_path'] = $item->getBindPath();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['field'] = $item->getField();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['condtion'] = $item->getCondtion();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['map_param'] = $item->getMapParam();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['map_where'] = $item->getMapWhere();
	            $result[$item->getModelName()][$item->getOp()][$item->getOpOrder()][$item->getId()]['map_type'] = $item->getMapType();
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
	    if(!isset($criteria['model_name'])||empty($criteria['model_name']))
	        return array();
	    
	    if(!isset($criteria['op'])||empty($criteria['op']))
	        return array();
	    
	    if(!isset($criteria['op_order']))
	        return array();

	    $info = $this->get('core.common')->S($this->stag);
	
	    if(empty($info))
	    {
	        $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
	        $this->get('core.common')->S($this->stag, $info, 86400);
	    }
	    
	    if(!isset($info[$criteria['model_name']][$criteria['op']][$criteria['op_order']]))
	        return array();
	
	    return $info[$criteria['model_name']][$criteria['op']][$criteria['op_order']];
	}
}