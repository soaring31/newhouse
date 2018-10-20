<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-04-13
*/

namespace CoreBundle\Security\Login;

use Symfony\Component\Security\Core\User\UserInterface;

interface LoginManagerInterface
{
    /**
     * @param string        $firewallName
     * @param UserInterface $user
     * @param Response|null $response
     *
     * @return void
     */
    public function loginUser($firewallName, UserInterface $user, $rememberMe=false);
}
