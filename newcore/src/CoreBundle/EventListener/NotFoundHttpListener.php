<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package Tripod
 * create date 2015-05-07
 */

namespace CoreBundle\EventListener;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * 未找到异常抛出
 */
class NotFoundHttpListener extends ServiceBase
{
    protected $parameters;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        if ($this->container->get('kernel')->getEnvironment() == "dev") {
            return;
        }

        $exception = $event->getException();

        // api处理异常
        if (strpos($event->getRequest()->getRequestUri(), '/api') === 0) {
            $code = $exception->getCode() ?: Response::HTTP_OK;
            $data = [
                'error' => 1,
                'data' => '',
                'code' => $code,
                'message' => $exception->getMessage()
            ];

            $response = new JsonResponse($data, $code);
            $event->setResponse($response);

            return;
        }

        $datatype = $this->get('request')->get('datatype', '');

        $data = array();
        $data['info'] = $exception->getMessage();
        $data['status'] = false;

        //判断是否为ajax提交
        if ($this->container->get('core.common')->isAjax() || $datatype == 'jsonp' || $datatype == 'json') {

            $jsonp = new JsonResponse($data);

            if ($datatype == 'jsonp')
                $jsonp->setCallback('n08cms');

            die($jsonp->getContent());
        }

        switch ($exception->getCode()) {
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
        $content = $this->container->get('templating')->render($template, $data);
        $response = new Response($content, $exception->getCode());
        $event->setResponse($response);

    }
}