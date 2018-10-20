<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月10日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 需求
* 
*/
class Demand extends AbstractServiceManager
{
    protected $table = 'Demand';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $pinfo=null, $isValid=true)
    {
        $user = parent::getUser();
        if(is_object($user))
            $groupId = $user->getUserinfo()->getGroupid();

        if (isset($groupId))
        {
            $config = self::get('house.userconfig')->findOneBy(array('role'=>$groupId), array(), false);
            if ($config && $user->getMid() != 1)
            {
                //当有会员配置时的操作
                $map = array();
                $map['operation'] = $user->getUsername();
                $map['create_time']['gt'] = strtotime(date('Y-m-d'));
                $map['name'] = '添加';
                $map['models'] = 'demand';
                
                //今日已发布数量
                $count = self::get('db.system_log')->count($map);

                //租售日发布数量
                $demandrenum = $config->getDemandrenum();

                if ($count < $demandrenum)
                    $result = parent::add($data, $pinfo, $isValid);
                else
                    throw new \LogicException("操作失败，您已经超过最大日发布数量!");
            } else
                //当没有会员配置时的操作,允许进行添加操作
                $result = parent::add($data, $pinfo, $isValid);

            //获取总积分
            $totalIntegral = $this->get('db.user_attr')->getIntegral();
            
            //$result->getTpye() 1为求购,2为求租
            if ($result->getType() == 1)
                $type = 'addsale';
            else
                $type = 'addrent';
            //积分增减
            $this->get('db.user_attr')->operateIntegral(array('name'=>$type.self::getTableName()));
    
            //积分记录
            $this->get('db.spend')->recordIntegral($totalIntegral,self::getTableName());
        } else
            $result = parent::add($data, $pinfo, $isValid);
        
        return $result;
    }
}