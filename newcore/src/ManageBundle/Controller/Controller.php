<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年1月8日
 */

namespace ManageBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Controller\Controller as BaseController;

/**
 * 基础控制器
 */
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

            $url = ucfirst(is_object($menu) ? $menu->getBundle() : $menu['bundle']) . "Bundle:";
            $url .= ucfirst(is_object($menu) ? $menu->getController() : $menu['controller']) . ":";
            $url .= is_object($menu) ? $menu->getAction() : $menu['action'];

            $data = $this->get('core.common')->getQueryParam(is_object($menu) ? $menu->getUrlparams() : $menu['urlparams']);

            foreach ($data as $key => $item) {
                $_REQUEST[$key] = $item;
            }

            //重定向路径
            $response = parent::forward($url, array(), $_REQUEST);
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
        $length = $this->get('request')->get('length', 5);
        $width = $this->get('request')->get('width', 100);
        $height = $this->get('request')->get('height', 30);
        $this->get('core.captcha')->setSessionWord($this->get('request')->get('name', ''));
        $this->get('core.captcha')->length = $length > 0 ? $length : 5;
        $this->get('core.captcha')->width = $width > 0 ? $width : 100;
        $this->get('core.captcha')->height = $height > 0 ? $height : 30;

        return $this->get('core.captcha')->generate_image();
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must activate the check in your security firewall configuration.');
    }

    public function render($bundle, array $parameters = array(), Response $response = null, $returnParameters = false)
    {
        //右main区域头部菜单
        $parameters['navurl'] = isset($parameters['navurl']) ? $parameters['navurl'] : $this->get('db.menus')->getMenuNav($this->getControllerName());
        return parent::render($bundle, $parameters, $response, $returnParameters);
    }

    public function render1($view, array $parameters = array(), Response $response = null)
    {
        //右main区域头部菜单
        $parameters['navurl'] = isset($parameters['navurl']) ? $parameters['navurl'] : $this->get('db.menus')->getMenuNav($this->getControllerName());
        return parent::render1($view, $parameters, $response);
    }
}