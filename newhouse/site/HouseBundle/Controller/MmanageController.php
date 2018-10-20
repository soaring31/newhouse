<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月23日
*/
namespace HouseBundle\Controller;
        
/**
* 管理后台
* @author house
*/
class MmanageController extends Controller
{
        


    /**
    * 菜单管理
    * house
    */
    public function mmanagelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 后台参数
    * house
    */
    public function mmanageparamsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.mconfig')->update($id, $data);
                }
            }else
                $this->get('db.mconfig')->add($data);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }            
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');
        
        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.mconfig')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 角色管理
    * house
    */
    public function authorityruleAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 成员管理
    * house
    */
    public function authorityaccessAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 管理员列表
    * house
    */
    public function mmanagersAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 工作统计
    * house
    */
    public function mworklistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}