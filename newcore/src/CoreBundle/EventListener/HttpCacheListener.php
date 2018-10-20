<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月8日
*/
namespace CoreBundle\EventListener;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HttpCacheListener extends ServiceBase
{
    protected $container;
    protected $request;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {   
        //主请求
        if(!$event->isMasterRequest()) {
            return;
        }
             
        //开发模式
        if($this->container->getParameter('kernel.environment') != 'prod') {
            return;
        }
        
        //是否开启了http缓存
        if(!$this->container->has('cache')) {
            return;
        }

        //兼容复原 Symfony 的 HttpCache 
        if(!method_exists($this->container->get('cache'),'getIgnores')) {
            return;
        }

        $store = $this->container->get('cache')->getStore();   
        
        $this->setRequest($event->getRequest());
        $ignorevalues = $this->getIgnoreValues($this->container->get('cache')->getIgnores());
        $response = $store->lookup($event->getRequest(),false,$ignorevalues);
        if($response&&$response->isFresh())
            $event->setResponse($response);

        return;
    }
    
    protected function setRequest(Request $request)
    {
        $this->request = $request;
    }
    
    protected function getRequest()
    {
        return $this->request;
    }
    
    protected function getIgnoreValues($ignores = array())
    {
        $ignorevalues = array();

        foreach($ignores as $ignore)
        {
            
            switch(strtolower($ignore))
            {
                
                case 'x-user-sessionarea':
                    $ignorevalues[$ignore] = 0;
                    if($this->getRequest()->hasSession())
                        $ignorevalues[$ignore] = array($this->get('core.area')->getArea());
                    
                    break;            
                case 'x-user-ismobile':                    
                    $ignorevalues[$ignore] = array($this->get('core.common')->isMobileClient() ? 1 : 0);
                    
                    break;
                case 'x-user-language':
                    $ignorevalues[$ignore] = array($this->getRequest()->getLocale());
                    
                    break;
            }            
        }

        return $ignorevalues;
    }

}