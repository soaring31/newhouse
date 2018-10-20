<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月19日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 用户扩展属性表
* 
*/
class UserAttr extends AbstractServiceManager
{
    protected $table = 'UserAttr';
    protected $storagetype = 1;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    /**
     * 积分的操作，根据规则表对积分的增减
     * @param array $criteria 对应规则的条件
     */
    public function operateIntegral(array $criteria=array())
    {
        $user = parent::getUser();
        $userInfo = $user->getUserInfo();
        
        $rules = $this->get('house.integral_attr')->findBy($criteria);

        foreach ($rules['data'] as $rule)
        {
            $integral1 = $this->get('house.integral')->getTableById($rule->getMid());
            //获取用户当前积分数
            $map = array();
            $map['mid'] = method_exists($userInfo, 'getId')?$userInfo->getId():0;
            $map['name'] = method_exists($integral1, 'getEname')?$integral1->getEname():'';
            $integral = self::findOneBy($map, array(), false);
            
            $data = array();
            $data['value'] = (method_exists($integral, 'getValue')?$integral->getValue():0) + (method_exists($rule, 'getValue')?$rule->getValue():0);
            if ($data['value']<0)
                $data['value'] = 0;
            
            if (is_object($integral))
            {
                //如果当前用户有积分，则根据规则进行增减积分
                $integral->setValue($data['value']);
                self::update($integral->getId(), array(), $integral, false);
            } else {
                //当前用户没有积分，则进行新增操作
                $data['name'] = $integral1->getEname();
                $data['title'] = $integral1->getName();
                $data['mid'] = $userInfo->getId();
            
                self::add($data, null, false);
            }
        }
    }
    
    /**
     * 获取积分
     * @return array
     */
    public function getIntegral()
    {
        $user = parent::getUser();
        $userInfo = method_exists($user, 'getUserinfo')?$user->getUserinfo():'';
        
        if(isset($userInfo) && $userInfo)
        {
            $map = array();
            $map['mid'] = $userInfo->getId();
            $map['findType'] = 1;
            $map['name']['orX'][]['name'] = 'jifen';
            $map['name']['orX'][]['name'] = 'jingjixinyong';
            $result = parent::findBy($map);
            
            $result1 = array();
            foreach ($result['data'] as $k=>$v)
            {
                $result1[$k]['title'] = $v['title'];
                $result1[$k]['name'] = $v['name'];
                $result1[$k]['value'] = $v['value'];
            }
            unset($result);
            return $result1;
        }
        return array();
    }
}