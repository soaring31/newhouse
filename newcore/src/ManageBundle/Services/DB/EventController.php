<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月13日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 控制器监听
* 
*/
class EventController extends AbstractServiceManager
{
    protected $table = 'EventController';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    /**
     * 单条数据查询
     * !CodeTemplates.overridecomment.nonjd!
     * @see \CoreBundle\Services\AbstractServiceManager::findOneBy()
     */
    public function findOneBy(array $criteria, array $orderBy = array(), $flag=true)
    {
        if(empty($criteria['id'])) {
            return array();
        }
        
        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_event_controller.yml";
    
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
        
        $info = isset($info[$criteria['id']])?$info[$criteria['id']]:array();
        
        if($info)
            $info['id'] = $info['ename'];
        
        return $info;
    }
    
    public function findBy(array $criteria, array $orderBy = null, $limit = 2000, $offset = 1, $groupBy='')
    {
        $result = array();
        $result['pageIndex'] = $offset;
        $result['pageSize'] = $limit;
        $result['pageCount'] = 0;
        $result['data'] = array();

        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_event_controller.yml";
        
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
        
        $result['pageCount'] = count($info);
        $result['data'] = $this->get('core.common')->pagination($offset,$limit,$info);
        
        return $result;
    }
    
    /**
     * 添加
     * @param array $data
     * @param \stdClass $info
     * @throws \InvalidArgumentException
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        self::_handleData($data);
    
        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_event_controller.yml";
    
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
    
        if(isset($info[$data['ename']]))
            throw new \LogicException(sprintf("英文名称 %s 已存在！！", $data['ename']));
            
        $info[$data['ename']] = $data;
    
        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);
    
        return $info[$data['ename']];
    }
    
    /**
     * 更新
     * @see \CoreBundle\Services\AbstractServiceManager::update()
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $data['ename'] = $id;

        self::_handleData($data);

        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_event_controller.yml";

        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();

        if(empty($data['ename'])||!isset($info[$data['ename']]))
            throw new \LogicException('数据不存在或已被删除！！');

        $info[$data['ename']] = array_merge($info[$data['ename']], $data);

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);

        return $info[$data['ename']];
    }
    
    /**
     * 删除
     */
    public function delete($id, $info='')
    {
        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_event_controller.yml";
    
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
    
        if(empty($id)||!isset($info[$id]))
            return true;
    
        unset($info[$id]);
    
        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);
    
        return true;
    }
    
    /**
     * 处理数据
     */
    private function _handleData(&$data)
    {
        unset($data['csrf_token']);
    
        if(!isset($data['name'])||empty($data['name']))
            throw new \LogicException('监听名称不能为空！！');

        if(!isset($data['ename'])||empty($data['ename']))
            throw new \LogicException('英文名称不能为空！！');
        
        if(!isset($data['url'])||empty($data['url']))
            throw new \LogicException('url不能为空！！');
        
        if(!isset($data['bindService'])||empty($data['bindService']))
            throw new \LogicException('绑定服务不能为空！！');
        
        //if (!$this->container->has($data['bindService']))
        //    throw new \InvalidArgumentException("[".$data['bindService']."]服务未注册。");
    
        if(!isset($data['mapType'])||(int)$data['mapType']<1)
            throw new \LogicException('请选择映射类型');
    }
}