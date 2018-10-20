<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月21日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class SitemapbindController extends Controller
{
        


    /**
    * 实现的show方法
    * admina
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
            $id = $this->get('request')->get('id', 0);
        
            if($id>0)
                $this->get('db.sitemapbind')->update($id, $_POST);
            else
                $this->get('db.sitemapbind')->add($_POST);
        
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
        $this->get('db.sitemapbind')->delete($id);
        return $this->success('操作成功');
    }
}