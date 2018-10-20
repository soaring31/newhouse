<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月27日
*/
namespace HouseBundle\Controller;
        
/**
* 管理商圈
* @author house
*/
class McatecirclemanageController extends Controller
{
        


    /**
    * 管理商圈
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
            $ids = $this->get('request')->get('id', 0);
            $data = $this->get('request')->request->all();
             if(!empty($ids))
            {
                $ids = explode(',',$ids);
                foreach($ids as $id)
                {
                    $this->get('house.cate_circle')->update($id, $data);
                }
                return $this->success('操作成功');
            }
        
            $this->get('house.cate_circle')->add($data);
                    
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
        $id = $this->get('request')->get('id', 0);
        $this->get('house.cate_circle')->delete($id);
        return $this->success('操作成功');
    }
}