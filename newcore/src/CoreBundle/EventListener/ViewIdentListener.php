<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月24日
*/
namespace CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Sensio\Bundle\FrameworkExtraBundle\EventListener\TemplateListener;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewIdentListener extends TemplateListener
{
    public function onKernelControllerss(FilterControllerEvent $event)
    {    
        parent::onKernelController($event);
    }
    
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {

    }
}