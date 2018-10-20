<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-24
 */

namespace WeixinBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Controller\Controller as BaseController;

class Controller extends BaseController
{
    public function _initialize()
    {

    }

    /**
     * 首页
     */
    public function indexAction()
    {
        $bundle = strtolower(substr($this->getBundleName(), 0, -6));
        $controller = $this->getControllerName();
        $action = $this->getActionName();
        $navurl = $this->get('db.menus')->findOneBy(array('bundle' => $bundle, 'controller' => $controller, 'action' => $action));

        $navurlMenu = is_object($navurl) ? $this->getMenus($navurl->getId()) : array();

        //判断是否有下级菜单
        if ($navurlMenu) {
            //取第一个元素
            $firstVal = reset($navurlMenu);
            $menu = $firstVal->menu;

            $url = ucfirst($menu->getBundle()) . "Bundle:";
            $url .= ucfirst($menu->getController()) . ":";
            $url .= $menu->getAction();
            //重定向路径
            $response = $this->forward($url, array(), $_REQUEST);
        } else {
            $navurlList = array();
            $navurlList['data'] = $navurl;
            $this->parameters['navurl'] = $navurlList;
            $response = $this->render1($this->getBundleName() . '::index.html.twig', $this->parameters);
        }

        //启用页面缓存
        //$response->setETag(md5($response->getContent()));
        //$response->setMaxAge(86400);
        //$response->setSharedMaxAge(86400);
        //$response->isNotModified($this->get('request'));

        return $response;
    }

    /**
     * 生成图形验证码
     */
    public function captchaAction()
    {
        //加载验证码服务
        $captchaImg = $this->container->get('core.captcha');
        $captchaImg->length = 5;
        $captchaImg->width = 150;
        $captchaImg->height = 33;

        $captchaImg->generate_image();
        return true;
    }

    public function render($bundle, array $parameters = array(), Response $response = null, $returnParameters = false)
    {
        //右main区域头部菜单
        $navurl = isset($parameters['navurl']) ? $parameters['navurl'] : $this->get('db.menus')->getMenuNav($this->getControllerName());
        $parameters['navurl'] = $navurl;
        return parent::render($bundle, $parameters, $response, $returnParameters);
    }

    public function render1($view, array $parameters = array(), Response $response = null)
    {
        //右main区域头部菜单
        $navurl = isset($this->parameters['navurl']) ? $this->parameters['navurl'] : $this->get('db.menus')->getMenuNav($this->getControllerName());
        $this->parameters['navurl'] = $navurl;
        return parent::render1($view, $parameters, $response);
    }
}
