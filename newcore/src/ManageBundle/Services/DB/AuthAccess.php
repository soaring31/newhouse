<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年04月21日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 访问控制表
* 
*/
class AuthAccess extends AbstractServiceManager
{
    protected $table = 'AuthAccess';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $info=null, $isValid=true)
    {
        $map = array();
        $map['username']['orX'][]['username'] = isset($data['username'])?$data['username']:'';
        $map['username']['orX'][]['email'] = isset($data['username'])?$data['username']:'';
        $map['username']['orX'][]['tel'] = isset($data['username'])?$data['username']:'';

        $user = $this->get('db.users')->findOneBy($map);
        
        if(!isset($data['uid'])||empty($data['uid']))
        { 
            if(!is_object($user))
                throw new \InvalidArgumentException("帐号不存在!");

            $data['uid'] = $user->getId();
        }

        //
        $this->get('db.auth_access_area')->changeAccessArea($data, $user);
        
        //变更组
        return $this->get('db.userinfo')->changeUserGroup($data['uid'], $data['group_id']);
    }

    public function delete($id, $info='')
    {
        return parent::dbalDelete(array('id'=>$id));
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $map = array();
        $map['id'] = $id;
        $user = $this->get('db.users')->findOneBy($map, array(), false);

        if(!is_object($user))
            throw new \InvalidArgumentException("帐号不存在!");

        //
        $this->get('db.auth_access_area')->changeAccessArea($data, $user);

        //变更组
        return $this->get('db.userinfo')->changeUserGroup($id, $data['group_id']);
    }
}