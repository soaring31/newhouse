<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年6月14日
*/
namespace CoreBundle\EventListener;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * 添加支持跨域访问(app支持)
 * @author Administrator
 *
 */
class CorsListener extends ServiceBase
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        //主请求
        if(!$event->isMasterRequest())
            return;

        $response = $event->getResponse();
        $responseHeaders = $response->headers;
    
        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $responseHeaders->set('Access-Control-Allow-Origin', '*');
        $responseHeaders->set('Access-Control-Allow-Credentials', 'true');
        //$responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
    
        $event->setResponse($response);
        
        //$httpRequestOrigin = $event->getRequest()->headers->get('origin');
    }
}