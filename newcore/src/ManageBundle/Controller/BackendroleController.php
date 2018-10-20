<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年01月27日
*/
namespace ManageBundle\Controller;
        
/**
* 角色管理
* @author admin
*/
class BackendroleController extends Controller
{
    /**
    * 弹窗页面
    * admin
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 保存
    * admin
    */
    public function saveAction()
    {
        
    }

    /**
    * 删除
    * admin
    */
    public function deleteAction()
    {
        
    }
}