<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年04月13日
*/
namespace ManageBundle\Controller;
        
/**
* 权限测试
* @author admin
*/
class AuthoritytestController extends Controller
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
    * admina
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
        
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.authtest')->update($id, $this->get('request')->request->all());
                }
            }else
                $this->get('db.authtest')->add($this->get('request')->request->all());
        
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
        $ids = $this->get('request')->get('id', '');
        
        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.authtest')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 实现的testa1方法
    * admina
    */
    public function testa1Action()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的testa3方法
    * admina
    */
    public function testa3Action()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}