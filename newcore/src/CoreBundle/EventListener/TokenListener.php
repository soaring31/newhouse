<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月8日
*/
namespace CoreBundle\EventListener;

use CoreBundle\Security\User\User;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use CoreBundle\Security\Http\TokenAuthenticatedInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TokenListener extends ServiceBase
{
    protected $tokens;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 前置事件
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller))
            return;

        //判断是否是异常抛出
        if ($controller[0] instanceof ExceptionController)
            return;

        /**
         * 仅在主请求下执行
         */
        if($event->isMasterRequest())
        {
            $bundle = $this->get('core.common')->getBundleName();

            $controllerName = $this->get('core.common')->getControllerName();

            try {
                $this->get('core.rbac')->createRule();
            } catch (\Exception $e) {
                
            }

            $user = $this->get('core.common')->getUser();

            //判断站点是否关闭(默认主站配置)
            $map = array();
            $map['area'] = 0;
            $map['ename'] = 'mvisit';
            $mconfig = $this->get('db.mconfig')->getData($map);

            $siteOpen = isset($mconfig['siteopen']['value'])?(int)$mconfig['siteopen']['value']:1;
            $siteTitle = isset($mconfig['reason']['value'])?$mconfig['reason']['value']:'';

            $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;

            $action = $this->get('core.common')->getActionName();

            //强制不受站点关闭影响的动作
            $passAction = array('geetest', 'login', 'captcha', 'logininfo', 'toolbar');

            if(!in_array($action, $passAction)&&$mid!=1&&$siteOpen==0)
                throw new \Exception($siteTitle);
            
            //处理微信数据
            if($bundle=="WeixinBundle"&&$controllerName!='call')
            {
                $wxid = $this->get('request')->get('_wxid','');

                if($wxid)
                {
                    //判断是否为数字，非数字则是加密过的ID
                    //if(!empty($wxid)&&!is_numeric($wxid))
                    $wxid = $this->get('core.common')->decode($wxid);

                    $map = array();
                    $map['id'] = $wxid;
                    //$map['uid'] = $user->getId();
                    $wxuser = $this->get('db.wxusers')->findOneBy($map, array(), false);

                    if(is_object($wxuser))
                    {
                        //存session
                        $event->getRequest()->getSession()->start();
                        $event->getRequest()->getSession()->set('appid', $wxuser->getAppid());
                        $event->getRequest()->getSession()->set('appsecret', $wxuser->getAppsecret());
                        $event->getRequest()->getSession()->set('wxtoken', $wxuser->getToken());
                    }
                }

                if(!$this->get('request')->getSession()->get('appid'))
                    throw new \Exception('无效的 微信公众号!');
            }
 
            //判断是否允许token认证
            if ($controller[0] instanceof TokenAuthenticatedInterface)
            {
                $token = $event->getRequest()->query->get('token');
    
                if(!is_object($user)&&$token)
                {
                    $user = $this->get('db.users')->findOneBy(array('token'=>$token), array(), false);
                    
                    if(!is_object($user))
                        throw new \Exception('无效的 token!');
                    
                    $user = new User($user);
                    
                    $this->get('core.rbac')->loginHandle($user);
                }
    
                //验证通过;标记一下回调的auth_token
                $event->getRequest()->attributes->set('auth_token', $token);
            }

            //匹配了08防火墙(匿名认证) 或 SF的pass防火墙(不认证)的请求会到这里。
            if(empty($user))
                return ;

            //只针对会员，对当前请求的权限分析，注意：子请求是不分析这个权限的
            if(!$this->get('core.rbac')->isGranted())
                throw new AccessDeniedException('无操作权限!');
        }
    }

    /**
     * 后置事件
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /**
         * 仅在主请求下执行
         */
        if($event->isMasterRequest())
        {            
            //读取控制器监听配置
            $eventController = $this->get('db.event_controller')->findBy(array());

            foreach($eventController['data'] as $item)
            {
                if(empty($item['url']))
                    continue;
            
                $matcher = new RequestMatcher($item['url']);
            
                if($matcher->matches($this->get('request')))
                {
                    switch((int)$item['mapType'])
                    {
                        //点击
                        case 1:
                            self::clickNum($item);
                            break;
                    }
                }
            
            }

            //检查是否设置了auth_token
            if (!$token = $event->getRequest()->attributes->get('auth_token'))
                return;
    
            $response = $event->getResponse();
            
            // 创建一个response header放入加密信息;
            $hash = sha1($response->getContent().$token);
    
            $response->headers->set('X-CONTENT-HASH', $hash);
        }
    }
    
    /**
     * 请求事件
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        //const MASTER_REQUEST = 1;
        //const SUB_REQUEST = 2;
        //当前请求类型
        $event->getRequest()->attributes->set('_request_type', $event->getRequestType());

        //判断是否主请求
        if(!$event->isMasterRequest())
            return;
        
        //dump($event->getRequest()->query->get('callback'));die();

    }
    
    /**
     * 点击数统计
     * @param array $item
     */
    protected function clickNum(array $item)
    {
        //绑定服务
        //$bindService = isset($item['bindService'])?$item['bindService']:'';
        $bindService = isset($item['bindService'])?$this->get('core.common')->getParams($item['bindService']):'';

        if(empty($bindService))
            return false;

        //映射字段
        $mapParam = isset($item['mapParam'])?$item['mapParam']:'';
        
        //映射条件
        $mapWhere = isset($item['mapWhere'])?$this->get('core.common')->getQueryParam($item['mapWhere']):'';
        
        $this->get($bindService)->dbalUpdate(array($mapParam=>array('sum'=>1)), $mapWhere);
        
        return true;
    }
}