<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月14日
*/
namespace CoreBundle\Services\Rbac\Auth;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Auth extends AuthAbstract
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }
}