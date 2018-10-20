<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月06日
*/
namespace HouseBundle\Controller;
        
/**
* 专题文档管理
* @author house
*/
class MtopicarcController extends Controller
{
        


    /**
    * 专题文档编辑
    * house
    */
    public function showAction()
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
            $fromid = $this->get('request')->get('fromid', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('house.topic_arc')->update($id, $data);
                }
            }else{
                $fromids = explode(',', $fromid);
                foreach($fromids as $fromid)
                {
                   $data['fromid'] = $fromid;
                   $this->get('house.topic_arc')->add($data); 
               }                
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
                $this->get('house.topic_arc')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 专题文档加载
    * house
    */
    public function loadarcsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}