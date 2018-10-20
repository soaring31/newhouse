<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月13日
*/
namespace HouseBundle\Controller;

/**
* 成员列表
* @author house
*/
class AuthorityaccessController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
	/**
    * 实现的show方法
    * admin
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * admin
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id', 0);
        
            if($id>0)
                $this->get('db.auth_access')->update($id, $_POST);
            else
                $this->get('db.auth_access')->add($_POST);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * admin
    */
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id', 0);
        $this->get('db.auth_access')->delete($id);
        return $this->success('操作成功');
    }
}