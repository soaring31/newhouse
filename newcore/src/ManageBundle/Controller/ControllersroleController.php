<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月29日
*/
namespace ManageBundle\Controller;

/**
* 系统角色
* @author admin
*/
class ControllersroleController extends Controller
{
    /**
    * 角色管理twig
    * admin
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id', 0);
            if($id>0)
                $this->get('db.auth_group')->update($id, $_POST);
            else
                $this->get('db.auth_group')->add($_POST);
            
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
}