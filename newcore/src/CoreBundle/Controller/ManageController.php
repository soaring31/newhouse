<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月19日
*/
namespace CoreBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
* 
* @author house
*/
class ManageController extends Controller
{
    /**
     * 实现的indexAction方法
     * admin
     */
    public function indexAction()
    {
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
            $url = $this->get('router')->generate('ilv_main');    

            //登陆处理
            if($this->get('core.login_manager')->loginHandle())
            {
                if($this->get('core.common')->isAjax())
                    $this->showMessage('登陆成功',true,array(),$url);
                 
                return new RedirectResponse($url);
            }
    
            return $this->error("登陆失败");
        }
    
        // $form = $this->createForm(new LoginType(), null, array('method' => 'POST'));
        $this->parameters['product'] = '诸葛找房后台管理-登录';
        // $this->parameters['form'] = $form->createView();
        $response = $this->render($this->getBundleName(), $this->parameters);
        $response->setSharedMaxAge(86400);
        return $response;
    }
    
    /**
     * 登陆后的首页
     * admin
     */
    public function mainAction()
    {
        $response = $this->render($this->getBundleName(), $this->parameters);
        $response->setSharedMaxAge(86400);
        return $response;
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
        $this->parameters['menus'] = $menuId?$this->getMenus($menuId):array();
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
}