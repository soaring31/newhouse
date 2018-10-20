<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月20日
*/
namespace CoreBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class AnonymousUser implements UserInterface
{    
    public function getUsername()
    {
        return null;
    }
    
    public function getPassword()
    {
        return null;
    }
    
    public function getRoles()
    {
        return array('AnonymousUser');
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function eraseCredentials()
    {
        return null;
    }
}