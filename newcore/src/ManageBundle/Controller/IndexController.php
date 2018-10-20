<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月8日
*/
namespace ManageBundle\Controller;

use ManageBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends Controller
{
    public function indexAction()
    {
        //判断是否已登入,是则直接进入
        if(is_object($this->getUser())){

            //生成登入成功后跳转路由
            $url = $this->get('router')->generate('manage_main');

            if($this->get('core.common')->isAjax())
                return $this->success('登入成功', $url);

            return new RedirectResponse($url);
        }

        //未登陆跳转登入界面
        $url = $this->get('router')->generate('manage_main');
        if($this->get('core.common')->isAjax())
            return $this->success('请重新登入', $url);
        return new RedirectResponse($url);
    }

    /**
     * 登陆动作
     */
    public function loginAction()
    {
        //生成登陆成功后跳转路由
        $url = $this->get('router')->generate('manage_main');
        if($this->get('request')->getMethod() == "POST")
        {
            //登陆处理
            if($this->get('core.login_manager')->loginHandle())
            {
                if($this->get('core.common')->isAjax())
                    $this->showMessage('登陆成功', true, array(), $url);

                return new RedirectResponse($url);
            }

            return $this->error("登陆失败");
        }

        if($this->get('core.common')->isAjax())
            return $this->error("登陆失效，请重新登陆",array('nologin'=>0));
        
        //判断是否已登入,是则直接进入
        if(is_object($this->getUser()))
        {        
            if($this->get('core.common')->isAjax())
                return $this->success('登入成功', $url);
        
            return new RedirectResponse($url);
        }

        $form = parent::createForm(new LoginType());
        $this->parameters['product'] = '诸葛找房后台管理-登录';
        $this->parameters['form'] = $form->createView();
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
}