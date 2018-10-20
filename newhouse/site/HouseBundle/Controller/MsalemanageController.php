<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月18日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class MsalemanageController extends Controller
{
    /**
    * 编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 手工刷新
     */
    public function refreshAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $data = $this->get('request')->request->all();
            
            $this->get('house.sale')->refresh($data);
        
            return $this->success('刷新成功');
        }
        
        return $this->error('刷新失败');
    }
    
    /**
     * 手工刷新置顶
     */
    public function refreshtopAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $data = $this->get('request')->request->all();
        
            $this->get('house.sale')->refreshtop($data);
        
            return $this->success('刷新成功');
        }
        
        return $this->error('刷新失败');
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
            //$user = parent::getUser();
            //$data['uid'] = $user->getId();
            //$data['checked'] = 0;
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('house.sale')->update($id, $data);
                }
            } else
                $this->get('house.sale')->add($data);

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
                $this->get('house.sale')->delete($id);
            }
        }
        
        return $this->success('操作成功');
    }
}