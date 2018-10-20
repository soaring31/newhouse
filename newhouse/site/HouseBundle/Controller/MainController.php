<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年3月30日
 */

namespace HouseBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MainController extends Controller
{
    /**
     * 实现的indexAction方法
     * admin
     */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 登陆后的首页
     * admin
     */
    public function mainAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function mainLoginAction()
    {
        return $this->login();
    }

    /**
     * 单点登录
     *
     * @return RedirectResponse
     */
    public function ssoLoginAction()
    {
        $sso         = $this->get('service_container')->getParameter('sso');
        $houseUrl    = $this->get('service_container')->getParameter('house_url');
        $redirectUrl = sprintf('%s/login?appKey=%s&redirect=%s', $sso['url'], $sso['appKey'],
            $houseUrl . $this->get('router')->generate('house_main'));

        return new RedirectResponse($redirectUrl);
    }

    /**
     * 单点登录
     *
     * @return RedirectResponse
     */
    public function passportLoginAction()
    {
        return new RedirectResponse('http://id.zhuge.com/Passport/Login/login');
    }

    /**
     * 实现的loginAction方法
     * admin
     */
    public function loginAction()
    {
        if ($this->get('house.zhugepassport.handler')->isSync()) {
            return $this->passportLoginAction();
        }

        return $this->login();
    }

    private function login()
    {
        //判断是否已登入,是则直接进入
        if (is_object($this->getUser())) {

            //生成登入成功后跳转路由
            $url = $this->get('router')->generate('house_main');

            if ($this->get('core.common')->isAjax())
                return $this->success('登入成功', $url);

            return new RedirectResponse($url);
        }
        $url = $this->get('router')->generate('house_main');

        if ($this->get('request')->getMethod() == "POST") {
            //生成登陆成功后跳转路由
            $url = $this->get('router')->generate('house_main');

            //登陆处理
            if ($this->get('core.login_manager')->loginHandle()) {
                if ($this->get('core.common')->isAjax())
                    $this->showMessage('登陆成功', true, array('csrf_token' => parent::createCsrfToken()), $url);

                return new RedirectResponse($url);
            }

            return $this->error("登陆失败");
        }

        $isApp = isset($_REQUEST['_isApp']) ? (int)$_REQUEST['_isApp'] : 0;
        if ($this->get('core.common')->isAjax() || $isApp == 1)
            return $this->error("登陆失效，请重新登陆", array('nologin' => 0));

        // $form = $this->createForm(new LoginType(), null, array('method' => 'POST'));
        $this->parameters['product'] = '诸葛找房后台管理-登录';
        // $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
     * 实现的topmenu方法
     * admin
     */
    public function topmenuAction()
    {
        //嵌入参数
        $this->parameters['menus'] = $this->getMenus(null, true);

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 左侧菜单
     * admin
     */
    public function leftmenuAction()
    {
        //根据菜单ID查看子菜单
        $menuId = $this->get('request')->get('menuId');

        //嵌入参数
        $this->parameters['menus'] = $menuId ? $this->getMenus($menuId) : array();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 登出动作
     * @throws \RuntimeException
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * 修改密码
     * house
     */
    public function passwordAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $this->get('db.users')->modPasswd();
            return $this->success('操作成功');
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 后台首页
     * house
     */
    public function mindexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 切换城市
     * house
     */
    public function mchangeAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 第三方登陆
     * house
     */
    public function thloginAction()
    {
        //判断是否已登入,是则直接进入
        if (!is_object($this->getUser())) {
            //生成登入成功后跳转路由
            $url = $this->get('router')->generate($this->container->getParameter('oauth.failed_auth_path'));

            if ($this->get('core.common')->isAjax())
                return $this->success('请重新登入', $url);

            return new RedirectResponse($url);
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 会员中心app
     * house
     */
    public function mindexblockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 头部
     * house
     */
    public function mindexheaderAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 发布
     * house
     */
    public function fabublockAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 登录头部
     * house
     */
    public function loginheaderAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function registerAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            $this->get('db.users')->bindOauth($data);
        }

        $url = $this->get('router')->generate($this->container->getParameter('oauth.default_target_path'));

        return parent::success('绑定成功', $url);
    }

    /**
     * 分销
     * house
     */
    public function fenxiaoblockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 功能搜索
     * house
     */
    public function msearchAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }
}