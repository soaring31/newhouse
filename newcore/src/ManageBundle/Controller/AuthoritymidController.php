<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月20日
*/
namespace ManageBundle\Controller;
        
/**
* 创始人管理
* @author admina
*/
class AuthoritymidController extends Controller
{
    /**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {
        //$info = $this->container->getParameter('doctrine.entity_managers');
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = $this->get('request')->get('id', 0);
        
            if($id>0)
                $this->get('db.users')->update($id, $this->get('request')->request->all());
            else
                $this->get('db.users')->add($this->get('request')->request->all());
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $this->get('db.users')->delete($id);
        return $this->success('操作成功');
    }
}