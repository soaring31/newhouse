<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月13日
*/
namespace ManageBundle\Controller;

/**
* 控制器监听
* @author house
*/
class ControllerlistenController extends Controller
{
    /**
    * 控制器监听表单
    * house
    */
    public function showAction()
    {
        $map = array();
        $map['id'] = $this->get('request')->get('_id');
        $info = $map['id']?$this->get('db.event_controller')->findOneBy($map):array();
        $form = $this->getFormFieldAttr('eventcontrollershow', $map, 'POST', $info, 'save');
        $this->parameters['form'] = $form->createView();
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
            $id = $this->get('request')->get('id', '');
            
            $data = $this->get('request')->request->all();
        
            if($id)
                $this->get('db.event_controller')->update($id, $data);
            else
                $this->get('db.event_controller')->add($data);
        
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
        $this->get('db.event_controller')->delete($id);
        return $this->success('操作成功');
    }
}