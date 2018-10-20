<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月25日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class MnewscatelistController extends Controller
{
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
                $this->get('db.category')->update($id, $data);
            else
                $this->get('db.category')->add($data);
        
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
        $this->get('db.category')->delete($id);
        return $this->success('操作成功');
    }

  

    /**
    * 编辑资讯分类
    * house
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    
    /**
     * 重置结构
     */
    public function structureAction()
    {
        $this->get('db.category')->createBinaryNode();
        
        $this->success('操作成功');
    }
}