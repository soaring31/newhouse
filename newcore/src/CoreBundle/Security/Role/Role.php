<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月21日
*/
namespace CoreBundle\Security\Role;

use Symfony\Component\Security\Core\Role\RoleInterface;

class Role implements RoleInterface
{
    private $id;
    private $role;
    public function __construct($roles)
    {
        $this->id = is_object($roles)?$roles->getId():0;
        $this->role = is_object($roles)?$roles->getName():'游客';
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getRole()
    {
        return $this->role;
    }
}