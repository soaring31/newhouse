<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年04月20日
*/
namespace ManageBundle\Controller;

use ManageBundle\Security\User\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
        
/**
* 
* @author admin
*/
class TestController extends Controller
{    
    public function loginAction()
    {
        //生成登陆成功后跳转路由
        $url = $this->get('router')->generate('manage_main');

        $map = array();
        $map['username'] = $this->get('request')->get('username');

        $userInfo = $this->get('db.authtest')->findOneBy($map);

        //登陆处理
        if($this->get('core.rbac')->loginHandle(new User($userInfo), 'mem_area'))
        {
            
        }

        if($this->get('core.login_manager')->otherLoginHandle('mem_area', $userInfo, 'ManageBundle\Security\User\User'))
        {
            if($this->get('core.common')->isAjax())
                $this->showMessage('登陆成功',true,array(),$url);
             
            return new RedirectResponse($url);
        }

        return $this->error("登陆失败");
    }

    /**
    * 实现的a_b方法
    * house
    */
    public function a_bAction()
    {
        
    }

    /**
    * 实现的aa方法
    * house
    */
    public function aaAction()
    {
        
    }

    /**
    * 实现的aa1方法
    * house
    */
    public function aa1Action()
    {
        
    }
}