<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月24日
*/
namespace HouseBundle\Controller;
        
/**
* 菜单管理
* @author house
*/
class MmenulistController extends Controller
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
    /*public function saveAction()
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
    } */ 
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
                    $this->get('db.menus')->update($id, $data);
                }
                return $this->success('操作成功');
            }
        
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

            $data = $this->get('request')->request->all();
            
            $info->setBindmenu(isset($data['bindmenu'])?implode(',', $data['bindmenu']):'');
            
            //$this->get('db.menus')->update($map['id'],array(), $info, false);//???
            $this->get('db.menus')->update($map['id'],$data, $info, false);//???
            
            return $this->success('操作成功');
        }
        
        if(is_object($info))
        {
            $map['bundle'] = array('eq'=>'house');
            
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

}