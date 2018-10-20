<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月19日
*/
namespace ManageBundle\Controller;

/**
 * 控制器管理
 */
class ControllersmanageController extends Controller
{
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 模版管理
     */
    public function tplmanageAction()
    {
        $bundle = $this->get('request')->get('searchName');
        $controller = $this->get('request')->get('name');

        $this->parameters['info'] = $this->get('db.views')->findBy(array('bundle'=>$bundle, 'controller'=>$controller,'is_delete'=>0));
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 模版修改
     */
    public function tplmodifyAction()
    {
        $id = (int)$this->get('request')->get('id',0);
        if($this->get('request')->getMethod() == "POST")
        {            
            if($id>0)
                $info = $this->get('db.views')->update($id, $_POST);
            else
                $info = $this->get('db.views')->add($_POST);              

            $data = array();
            $data['id'] = $info->getId();
            $data['cTime'] = date('Y-m-d H:i', $info->getCTime());
            $data['modurl'] = $this->get('core.common')->U('tplmodify',array('id'=>$data['id']));
            $data['delurl'] = $this->get('core.common')->U('tpldelete',array('id'=>$data['id']));
            return $this->success('操作成功', '', false, $data);
        }
        
        return  $this->error('操作失败');
    }
    
    /**
     * 模版删除
     */
    public function tpldeleteAction()
    {
        $id = $this->get('request')->get('id');
        if($this->get('request')->getMethod() == "POST")
        {
            $this->get('db.views')->delete($id);
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
    
    /**
     * 新增控制器
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $this->get('core.controller.command')->createController($_POST);
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
    /**
     * 控制器预览
     */
    public function previewAction()
    {        
        $this->parameters['tpl'] = $this->get('core.controller.command')->previewController($_REQUEST);
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 添加方法
     */
    public function createactionAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            //调用服务执行生成动作
            $this->get('core.controller.command')->CreateAction($_POST, $this->getUser());
            return $this->success('操作成功');
        }
        
        $map = array();
        $belongbundle = $this->get('request')->get('searchName');
        $belongcontroller = $this->get('request')->get('name');
        
        $form = $this->getFormFieldAttr('controllerscreateaction', $map, 'POST', null, 'createaction');
        $form->add('belongbundle', 'hidden' , array(
            'label'=>'所属Bundle'
            , 'attr'=>array('value'=>$belongbundle)
        ));
        
        $form->add('belongcontroller', 'hidden' , array(
            'label'=>'所属控制器'
            , 'attr'=>array('value'=>$belongcontroller)
        ));
        
        $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
    }
}