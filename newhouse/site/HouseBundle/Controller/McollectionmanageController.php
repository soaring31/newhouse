<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月28日
*/
namespace HouseBundle\Controller;
        
/**
* 管理合辑
* @author house
*/
class McollectionmanageController extends Controller
{
    /**
    * 加载资讯
    * house
    */
    public function loadnewsAction()
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
            if(!empty($ids))
            {
                $ids = explode(',',$ids);
                foreach($ids as $id)
                {
                    $this->get('house.aalbums')->update($id, $data);
                }
                return $this->success('操作成功');
            }

            $pid = explode(',', $this->get('request')->get('pid', ''));
            $pid = empty($pid)?array(0):$pid;
            foreach($pid as $v)
            {
                $data['pid'] = $v;
                
                $this->get('house.aalbums')->add($data);
            }

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
                $this->get('house.aalbums')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}