<?php
/**
 * @copyright Copyright (c) 2012 – 2020 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年10月9日
 */
namespace CoreBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\EventListener\RouterListener AS BaseRouterListener;

/**
 * 路由监听
 */
class RouteListener extends BaseRouterListener
{
    private $matcher;
    private $context;
    private $logger;
    private $request;
    private $requestStack;

    /**
     * Constructor.
     *
     * RequestStack will become required in 3.0.
     *
     * @param UrlMatcherInterface|RequestMatcherInterface $matcher      The Url or Request matcher
     * @param RequestContext|null                         $context      The RequestContext (can be null when $matcher implements RequestContextAwareInterface)
     * @param LoggerInterface|null                        $logger       The logger
     * @param RequestStack|null                           $requestStack A RequestStack instance
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($matcher, RequestContext $context = null, LoggerInterface $logger = null, RequestStack $requestStack = null)
    {
        parent::__construct($matcher, $context, $logger, $requestStack);

        $this->matcher = $matcher;
        $this->context = $context ?: $matcher->getContext();
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
       
        $parameters = array();
        $request = $event->getRequest();
        $container = $event->getDispatcher()->getContainer();
        //主请求才生效
        if($event->isMasterRequest())
        {
            //如果是json或jsonp提交，则改为post
            $dataType = (bool)$request->get('_isApp', '');
            
            if($dataType){
                $request->setMethod('POST');
                $request->request->replace($request->query->all());
            }

            $container->get('core.area')->setArea();
        }

        if (null !== $this->requestStack)
            $this->setRequest($request);

        if ($request->attributes->has('_controller'))
            return;

        // add attributes based on the request (routing)
        try {
            // matching a request is more powerful than matching a URL path + context, so try that first
            if ($this->matcher instanceof RequestMatcherInterface)
                $parameters = $this->matcher->matchRequest($request);
            else
                $parameters = $this->matcher->match($request->getPathInfo());

            if (null !== $this->logger)
                $this->logger->info(sprintf('Matched route "%s" (parameters: %s)', $parameters['_route'], $this->parametersToString($parameters)));

            $request->attributes->add($parameters);
            unset($parameters['_route'], $parameters['_controller']);
            $request->attributes->set('_route_params', $parameters);
        } catch (ResourceNotFoundException $e) {
            
            $pathInfo = $request->getPathInfo();
            $pathInfo = explode("/",trim($pathInfo,"/"));

            $siteBundle = $container->getParameter('site_bundle');
            
            $defaultPrefix = $container->getParameter('_defaultprefix');
            
            $bundles = array();            
            $bundleArr = array();  
            $_bundles = $container->get('core.common')->getBundles();
            foreach($_bundles as $item)
            {
                $_item = explode('\\',$item);
            
                //去掉最后一个数组
                array_pop($_item);
            
                $bundles[end($_item)] = implode('\\', $_item);
            
                $bundleArr[strtolower(str_replace("Bundle","",end($_item)))] = "";
            }

            if(!isset($bundleArr[$pathInfo[0]]))
                array_unshift($pathInfo,$defaultPrefix);

            $bundle = isset($pathInfo[0])&&$pathInfo[0]?ucfirst($pathInfo[0])."Bundle":$siteBundle;
            $controller = isset($pathInfo[1])&&$pathInfo[1]?ucfirst($pathInfo[1])."Controller":"IndexController";
            $action = isset($pathInfo[2])&&$pathInfo[2]?strtolower($pathInfo[2])."Action":"indexAction";

            if(!isset($bundles[$bundle]))
                throw new \InvalidArgumentException('无效的Bundle:['.$bundle.']', 403);        
            
            $controllerpath	= $bundles[$bundle]."\\Controller\\".$controller."::".$action;
            $routepath = (isset($pathInfo[0])?strtolower($pathInfo[0]):"home")."_".(isset($pathInfo[2])?strtolower($pathInfo[2]):"index");

            try {
                $object = new \ReflectionClass($bundles[$bundle]."\\Controller\\".$controller);
                $object->getMethod($action);
            } catch (\Exception $e){
                throw new \UnexpectedValueException ('无效的控制器:['.$controllerpath.']', 404);
            }

            $parameters['_controller'] = $controllerpath;     
            $parameters['_route'] = $routepath;
            $request->attributes->add($parameters);
            unset($parameters['_route'], $parameters['_controller']);
            $request->attributes->set('_route_params', $parameters);
            
        } catch (MethodNotAllowedException $e) {
            $message = sprintf('No route found for "%s %s": Method Not Allowed (Allow: %s)', $request->getMethod(), $request->getPathInfo(), implode(', ', $e->getAllowedMethods()));

            throw new MethodNotAllowedHttpException($e->getAllowedMethods(), $message, $e, 403);
        }     
    }

    private function parametersToString(array $parameters)
    {
        $pieces = array();
        foreach ($parameters as $key => $val) {
            $pieces[] = sprintf('"%s": "%s"', $key, (is_string($val) ? $val : json_encode($val)));
        }
        return implode(', ', $pieces);
    }
}