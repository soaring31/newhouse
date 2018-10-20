<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月12日
*/
namespace CoreBundle\EventListener;

use CoreBundle\Services\ServiceBase;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DbLifecycleListener extends ServiceBase
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * 更新前
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {        
        //查询条件
        $map = array();
        $map['op'] = "update";
        $map['op_order'] = 1;

        //const MASTER_REQUEST = 1;
        //const SUB_REQUEST = 2;
        //当前请求类型
        $requestType = $this->get('request')->attributes->get('_request_type');
        if($requestType!=1)
            return true;
        self::_haldeEventListen($args, $map);

        return true;
    }
    
    /**
     * 更新后
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        //查询条件
        $map = array();
        $map['op'] = "update";
        $map['op_order'] = 2;
        
        self::_haldeEventListen($args, $map);

        return true;
    }
    
    /**
     * 新增前
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {        
        //查询条件
        $map = array();
        $map['op'] = "insert";
        $map['op_order'] = 1;
        
        self::_haldeEventListen($args, $map);

        return true;
    }

    /**
     * 新增后 
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {       
        //查询条件
        $map = array();
        $map['op'] = "insert";
        $map['op_order'] = 2;
        
        self::_haldeEventListen($args, $map);

        return true;
    }
    
    /**
     * 删除前
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {        
        //查询条件
        $map = array();
        $map['op'] = "delete";
        $map['op_order'] = 1;
        
        self::_haldeEventListen($args, $map);

        return true;
    }
    
    /**
     * 删除后
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        //查询条件
        $map = array();
        $map['op'] = "delete";
        $map['op_order'] = 2;
        
        self::_haldeEventListen($args, $map);

        return true;
    }
    
    /**
     * 读取后
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        //查询条件
//         $map = array();
//         $map['op'] = "select";
//         $map['op_order'] = 2;
        
//         self::_haldeEventListen($args, $map);

        return true;
    }

    private function _getEventListen($map)
    {
        if ($this->get('request')->getMethod() == 'DELETE')            
            $map['op'] = 'delete';

        return $this->get('db.event_listen')->getData($map);
    }

    private function _haldeEventListen(LifecycleEventArgs $args, array $map)
    {
        $obj = $args->getObject();
        
        $entityManager = $args->getObjectManager()->getClassMetadata(get_class($obj));
        
        $map['model_name'] = $this->get('core.common')->unprefixName($entityManager->getTableName());

        $eventListen = self::_getEventListen($map);

        if($eventListen)
            $this->get('core.common')->handleEventListen($eventListen, $obj, $map['model_name']);

        return true;
    }
}