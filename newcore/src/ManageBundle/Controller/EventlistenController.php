<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月13日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class EventlistenController extends Controller
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
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.event_listen')->update($id, $data);
                }
            }else
                $this->get('db.event_listen')->add($data);
        
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
                $this->get('db.event_listen')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}