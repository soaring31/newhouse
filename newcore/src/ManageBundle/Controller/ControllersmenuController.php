<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月29日
*/
namespace ManageBundle\Controller;
        
/**
* 系统菜单管理
* @author admin
*/
class ControllersmenuController extends Controller
{
        
    private $formIdent = "backendmenushow";

    /**
    * 菜单管理twig
    * admin
    */
    public function showAction()
    {
        $map = array();
        $map['id'] = (int)$this->get('request')->get('id',0);
        $pid = (int)$this->get('request')->get('pid',0);

        $info = $this->get('db.menus')->findOneBy($map);

        $form = $this->getFormFieldAttr($this->formIdent, $map, 'POST', $info, 'save');

        $pid = is_object($info)?$info->getPid():$pid;

        if($pid>0)
        {
            $pinfo = $this->get('db.menus')->findOneBy(array('id'=>$pid));
            if(is_object($pinfo))
            {
                $choiceId = $pinfo->getId();
                $choicesVal = $pinfo->getName();
                $options = array();
                $options['label'] = "上级菜单";
                $options['choices'] = array($choiceId=>$choicesVal);
                $options['attr'] = array('style'=>'padding:0px 10px;');
                $form->add('pid', 'choice', $options);
            }
        }
        
        $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
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
    
    /**
     * 保存
     * admin
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');
            $formInfo = $this->get('db.model_form')->findOneBy(array('name'=>$this->formIdent));
            $formId = is_object($formInfo)?$formInfo->getId():0;
            if($id>0)
                $this->get('db.menus')->update($id, $_POST);
            else{
                $info = $this->get('db.menus')->add($_POST);
                $id = $info->getId();
            }
    
            $data = array();
            $data['modurl'] = $this->get('core.common')->U('save',array('id'=>$id, '_form_id'=>$formId));
            $data['delurl'] = $this->get('core.common')->U('delete',array('id'=>$id, '_form_id'=>$formId));
            return $this->success('操作成功', '', false, $data);
        }
        return $this->error('操作失败');
    }
    
    /**
     * 删除
     * admin
     */
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id');
    
        if($id>0)
        {
            $this->get('db.menus')->delete($id);
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
    
    /**
     * 关联菜单
     */
    public function bindmenuAction()
    {
        $map = array();
        $bindInfo = array();
        $map['id'] = (int)$this->get('request')->get('pid', 0);
        $info = $this->get('db.menus')->findOneBy($map);
        
        if($this->get('request')->getMethod() == "POST")
        {
            if($map['id']<=0)
                return $this->erro('参数错误');

            $info->setBindmenu(isset($_POST['bindmenu'])?implode(',', $_POST['bindmenu']):'');

            //$this->get('db.menus')->update($map['id'],$_POST, $info, false);
            $this->get('db.menus')->update($map['id'],array(), $info, false);
            
            return $this->success('操作成功');
        }
        
        if(is_object($info))
        {
            $map['bundle'] = array('eq'=>'manage');
            
            $level = $info->getLevel();
            $map['level'] = $level+1;
            $map['pid']['neq'] = $map['id'];            
            unset($map['id']);
            $_bindInfo = $this->get('db.menus')->findBy($map);
            
            if(isset($_bindInfo['data']))
            {
                foreach($_bindInfo['data'] as $vo)
                {
                    $bindInfo[$vo->getPid()][$vo->getId()] = $vo->getName();
                }
            }
        }

        $this->parameters['info'] = $info;
        $this->parameters['bindInfo'] = $bindInfo;
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 重置结构
     */
    public function structureAction()
    {
        $this->get('db.menus')->createBinaryNode();
        
        $this->success('操作成功');
    }
    
    /**
     * 重置缓存
     */
    public function cacheAction()
    {
        $this->get('db.menus')->retCache();
    
        $this->success('操作成功');
    }
}