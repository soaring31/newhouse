<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\Controller;

use ManageBundle\Form\LoginType;
use Symfony\Component\HttpFoundation\RedirectResponse;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
        
        //启用缓存
        $response = $this->render($this->getBundleName(), $this->parameters);
        $response->setSharedMaxAge(86400);
        return $response;
    }
    
    /**
    * 实现的loginAction方法
    * admin
    */
    public function loginAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            //生成登陆成功后跳转路由
            $url = $this->get('router')->generate('oauth_main');

            //登陆处理
            if($this->get('core.login_manager')->loginHandle())
            {
                if($this->get('core.common')->isAjax())
                    $this->showMessage('登陆成功',true,array(),$url);
                 
                return new RedirectResponse($url);
            }
            
            return $this->error("登陆失败");
        }
        
        $form = $this->createForm(new LoginType(), null, array(
            'action' => $this->generateUrl('oauth_login'),
            'method' => 'POST'));
        $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
    * 登陆后的首页
    * admin
    */
    public function mainAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
        
        //启用缓存
        $response = $this->render($this->getBundleName(), $this->parameters);
        $response->setSharedMaxAge(86400);
        return $response;
    }
    
    /**
     * 检测是否登陆
     * @throws \RuntimeException
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must activate the check in your security firewall configuration.');
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
