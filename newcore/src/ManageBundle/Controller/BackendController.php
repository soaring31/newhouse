<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年01月27日
*/
namespace ManageBundle\Controller;
        
/**
* 管理后台
* @author admin
*/
class BackendController extends Controller
{    
    /**
    * 菜单管理
    * admin
    */
    public function backendmenuAction()
    {
        //嵌入参数
        $this->parameters['info'] = $this->getMenus();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 角色管理
    * admin
    */
    public function backendroleAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 模版管理
    * admin
    */
    public function backendtplAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}