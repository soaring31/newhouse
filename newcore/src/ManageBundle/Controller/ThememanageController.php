<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月11日
*/
namespace ManageBundle\Controller;
        
/**
* 主题管理
* @author admin
*/
class ThememanageController extends Controller
{
    /**
    * 主题管理show方法
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
            $id = $this->get('request')->get('id', 0);
        
            if($id>0)
                $this->get('db.themes')->update($id, $_POST);
            else
                $this->get('db.themes')->add($_POST);
        
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
        $id = $this->get('request')->get('id', 0);
        $this->get('db.themes')->delete($id);
        return $this->success('操作成功');
    }

    /**
    * 更新资源包
    * admin
    */
    public function upsourceAction()
    {
        $id = (int)$this->get('request')->get('_id', 0);
        $info = $this->get('db.themes')->findOneBy(array('id'=>$id));
        
        if(empty($info))
            return $this->error('该主题被不存在或已被删除!');
        
        $ename = $info->getEname();
        if($ename)            $this->get('core.common')->upResource($ename, '08themes');
        return $this->success('更新主题成功!');
    }
}