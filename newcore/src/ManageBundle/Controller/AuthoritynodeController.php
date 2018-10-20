<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年04月06日
*/
namespace ManageBundle\Controller;
        
/**
* 节点管理
* @author admin
*/
class AuthoritynodeController extends Controller
{
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
                $this->get('db.auth_rule')->update($id, $this->get('request')->request->all());
            else
                $this->get('db.auth_rule')->add($this->get('request')->request->all());
        
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
        $this->get('db.auth_rule')->delete($id);
        return $this->success('操作成功');
    }
}