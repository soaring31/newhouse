<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月23日
*/
namespace CoreBundle\EventListener\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ExceptionListener as BaseExceptionListener;

class ExceptionListener extends BaseExceptionListener
{
    protected function setTargetPath(Request $request)
    {
        // Do not save target path for XHR requests
        // You can add any more logic here you want
        // Note that non-GET requests are already ignored
        if ($request->isXmlHttpRequest()) {
            return;
        }

        parent::setTargetPath($request);
    }
}