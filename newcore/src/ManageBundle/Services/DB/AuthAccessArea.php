<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年01月11日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 访问控制表
* 
*/
class AuthAccessArea extends AbstractServiceManager
{
    protected $table = 'AuthAccessArea';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        if(!isset($data['uid'])||empty($data['uid']))
        {
            if(!isset($data['username'])||empty($data['username']))
                throw new \InvalidArgumentException("会员帐号不能为空!");
            
            $user = $this->get('db.users')->findOneBy(array('username'=>$data['username']));
    
            if(!is_object($user))
                throw new \InvalidArgumentException("帐号不存在!");
    
            $data['uid'] = $user->getId();
        }
        
        if(!isset($data['uid'])||(int)$data['uid']<=0)
            throw new \InvalidArgumentException("无效的会员!");
    
        $count = parent::count(array('uid'=>$data['uid']));
    
        //有重复的则不需要添加
        if($count>0)
            throw new \InvalidArgumentException("该会员已是其它分站管理!");

        return parent::add($data, $info, $isValid);
    }
    
    public function delete($id, $info='')
    {
        return parent::dbalDelete(array('id'=>$id));
    }
    
    public function changeAccessArea($data, $userInfo=null)
    {
        if (null === $userInfo)
        {
            $map = array();
            if (isset($data['id']))
            {
                $map['id'] = $data['id'];
            } else {
                $map['username']['orX'][]['username'] = isset($data['username'])?$data['username']:'';
                $map['username']['orX'][]['email'] = isset($data['username'])?$data['username']:'';
                $map['username']['orX'][]['tel'] = isset($data['username'])?$data['username']:'';
            }
            
            $userInfo = $this->get('db.users')->findOneBy($map, array(), false);
        }
     
        if (is_object($userInfo))
        {
            $map = array();
            $map['uid'] = $userInfo->getId();
            $result = self::findOneBy($map, array(), false);

            // 判断有无地区id,有则进行更新或者添加，没有则进行删除
            if (isset($data['aid']))
            {
                if (is_object($result))
                {
                    $result->setUid($userInfo->getId());
                    $result->setAid($data['aid']);
                    self::update($result->getId(), array(), $result, false);
                }
                else
                {
                    $data1 = array();
                    $data1['uid'] = $userInfo->getId();
                    $data1['aid'] = $data['aid'];
                    self::add($data1);
                }
            } else {
                if (is_object($result))
                {
                    if ($result->getGroupId() == $data['group_id'])
                        return ;
                    $map = array();
                    $map['id']['in'] = $result->getGroupId().','.$data['group_id'];
                    $result1 = $this->get('db.mem_group')->findBy($map);

                    // 如果分类会员组不相同，则进行删除操作(db.mem_group 中的  aid 相同则视为分类会员组相同)
                    if (2 != count($result1['data']) || $result1['data'][0]->getAid() != $result1['data'][1]->getAid())
                        self::delete($result->getId());
                }
            }
        }
    }
}