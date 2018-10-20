<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月28日
*/
namespace HouseBundle\Controller;
        
/**
* 销售状态
* @author house
*/
class McatestatusmanageController extends Controller
{
    /**
    * 显示
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
            $id = $this->get('request')->get('id', 0);
            $data = $this->get('request')->request->all();
            if($id>0)
                $this->get('house.cate_status')->update($id, $data);
            else
                $this->get('house.cate_status')->add($data);
        
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
        $this->get('house.cate_status')->delete($id);
        return $this->success('操作成功');
    }
}