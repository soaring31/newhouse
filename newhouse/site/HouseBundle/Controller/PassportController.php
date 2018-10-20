<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月11日
*/
namespace HouseBundle\Controller;

/**
* 前台登录注册
* @author house
*/
class PassportController extends Controller
{



    /**
    * 登录
    * house
    */
    public function loginAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 注册
    * house
    */
    public function registerAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 找回密码
    * house
    */
    public function find_passwordAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 修改密码
    * house
    */
    public function change_passwordAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 绑定手机
    * house
    */
    public function bindAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}