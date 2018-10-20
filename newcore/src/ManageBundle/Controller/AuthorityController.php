<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年04月06日
*/
namespace ManageBundle\Controller;

/**
* 权限管理
* @author admin
*/
class AuthorityController extends Controller
{
    /**
    * 节点管理
    * admin
    */
    public function authoritynodeAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 角色管理
    * admin
    */
    public function authorityruleAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 访问控制列表
    * admin
    */
    public function authorityaccessAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 权限测试
    * admin
    */
    public function authoritytestAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 防火墙管理
    * admin
    */
    public function authorityfirewallAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 创始人管理
    * admina
    */
    public function authoritymidAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}