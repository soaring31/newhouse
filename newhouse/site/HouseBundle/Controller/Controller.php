<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-05-18
 */

namespace HouseBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Controller\Controller as BaseController;

class Controller extends BaseController
{
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
            $response = $this->render1('ManageBundle::index.html.twig', $this->parameters);
        }

        return $response;
    }

    /**
     * 生成图形验证码
     */
    public function captchaAction()
    {
        //加载验证码服务
        $captchaImg = $this->get('core.captcha');
        $captchaImg->length = 5;
        $captchaImg->width = 150;
        $captchaImg->height = 33;

        $captchaImg->generate_image();
        return true;
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

    /**
     * 获取菜单
     * @param int $menuid
     * @return array
     */
    public function getMenus($menuid = null, $ishide = false)
    {
        //判断是否登陆
        if (!$this->getUser())
            return array();

        //判断是否为数字，非数字则是加密过的ID
        if (!empty($menuid) && !is_numeric($menuid))
            $menuid = $this->get('core.common')->decode($menuid);

        $pid = $menuid > 0 ? $menuid : 0;

        return $this->get('db.menus')->getMenuList($pid, array('sort' => 'asc', 'id' => 'asc'), $ishide);
    }

    /**
     * 模板选择
     * @param array $str
     * @return string
     */
    public function showTpl(array $data = array())
    {
        $resultData = array();
        $tpl = $this->get('request')->get('tpl', '');
        if ($tpl) {
            $tmpArray = array();
            $tmpArray = explode(':', $tpl);

            //格式: AutoBundle:Index:submenu
            if (count($tmpArray) != 3)
                return $this->error('tpl参数异常');

            //取最后一个submenu作为返回值变量名
            $resultData[$tmpArray[2]] = $data;

            if (count($tmpArray) != 3)
                return $this->error('tpl参数异常');

            //取数组最后一个元素submenu作为返回值变量名
            $resultData[$tmpArray[2]] = $data;

            return $this->render1($tpl . '.html.twig', $resultData);
        } else {
            $action = $this->getActionName();

            //不带tpl参数，返回值数组名为方法名
            $resultData[$action] = $data;

            return $this->render($this->getBundleName(), $resultData);
        }
    }
}
