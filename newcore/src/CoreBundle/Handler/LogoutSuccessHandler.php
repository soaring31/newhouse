<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年5月6日
 */

namespace CoreBundle\Handler;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler extends ServiceBase implements LogoutSuccessHandlerInterface
{
    public function __construct(ContainerInterface $container, Router $router)
    {
        $this->container = $container;
    }

    public function onLogoutSuccess(Request $request)
    {
        $referer_url = $request->headers->get('referer', '/');

        // passport 退出登录
        if ($this->get('house.zhugepassport.handler')->isSync()) {
            $this->get('house.zhugepassport.handler')->handleLogout($request, $referer_url);
        }

        if ($request->isXmlHttpRequest()) {

            //ajax请求
            return new JsonResponse(array(
                'status' => true,
                'code'   => 0,
                'info'   => '登出成功',
                'url'    => $referer_url,
            ));
        }

//        if (strpos($referer_url, '/main') === 0) {
//            return new RedirectResponse($referer_url . '/login');
//        }

        $referer_url = '/';

        // 直接跳首页
        return new RedirectResponse($referer_url);
    }
}