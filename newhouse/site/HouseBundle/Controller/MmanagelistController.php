<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月23日
*/
namespace HouseBundle\Controller;
        
/**
* 菜单管理
* @author house
*/
class MmanagelistController extends Controller
{
        
    private $formIdent = "menus";

    /**
    * 菜单编辑
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
                $this->get('db.menus')->update($id, $data);
            else
                $this->get('db.menus')->add($data);
        
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
        $this->get('db.menus')->delete($id);
        return $this->success('操作成功');
    }
    
    /**
    * 添加子菜单
    * admin
    */
    public function detailsAction()
    {
        $pid = (int)$this->get('request')->get('pid',0);
        
        $formInfo = $this->get('db.model_form')->findOneBy(array('name'=>$this->formIdent));
        
        //嵌入参数        
        $this->parameters['formId'] = is_object($formInfo)?$formInfo->getId():0;
        $this->parameters['info'] = $this->get('db.menus')->findBy(array('pid'=>$pid));
        return $this->render($this->getBundleName(), $this->parameters);
    }

}