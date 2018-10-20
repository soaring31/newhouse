<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月22日
*/
namespace CoreBundle\Profiler;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AnonymouslyMatcher extends ServiceBase implements RequestMatcherInterface
{
    protected $container;
    protected $authorizationChecker;
    
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, ContainerInterface $container)
    {
        $this->container = $container;
        $this->authorizationChecker = $authorizationChecker;
    }
    
    public function matches(Request $request)
    {
        //return $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN');
    }
}