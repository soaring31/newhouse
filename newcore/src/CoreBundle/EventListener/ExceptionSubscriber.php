<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年2月22日
*/
namespace CoreBundle\EventListener;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


class ExceptionSubscriber extends ServiceBase implements EventSubscriberInterface
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            KernelEvents::EXCEPTION => array(
                array('processException', 10),
                array('logException', 0),
                array('notifyException', -10),
            )
        );
    }
    
    public function processException(GetResponseForExceptionEvent $event)
    {
        //dump('processException', $event);die();
    }
    
    public function logException(GetResponseForExceptionEvent $event)
    {
        //dump('logException', $event);die();
    }
    
    public function notifyException(GetResponseForExceptionEvent $event)
    {
        //dump('notifyException', $event);die();
        $exception = $event->getException();
        
        $parameters = array();
        $parameters['code'] = (int)$exception->getCode();
        $parameters['info'] = $exception->getMessage();
        
        //判断是否为ajax提交
        if($this->get('request')->isXmlHttpRequest())
        {
            //throw new \InvalidArgumentException($this->parameters['error']);
            //ajax请求
            return new JsonResponse(array(
                'status' => true,
                'code' => $parameters['code'],
                'info' => $parameters['info'],
                'url' => '',
            ));
        }
        
        switch($parameters['code'])
        {
            case 403:
                $template = 'CoreBundle:Dispatch:error403.html.twig';
                break;
            case 404:
                $template = 'CoreBundle:Dispatch:error404.html.twig';
                break;
            default:
                $template = 'CoreBundle:Dispatch:error.html.twig';
                break;
        }
        
        if($this->get('kernel')->getEnvironment() != "dev")
            return $this->container->get('templating')->render($template, $parameters);
    }
}