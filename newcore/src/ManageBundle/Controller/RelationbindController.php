<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月09日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author house
*/
class RelationbindController extends Controller
{       
	public function bindsaveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = $this->get('request')->get('id', '');
            $master = $this->get('request')->get('master', '');
        	$slave = $this->get('request')->get('parent_form', '');
        	
            if(!empty($id) && !empty($master)){
            	$result = $this->get('db_relation')->findOneBy(array('id'=>$id));
            	$cfgs = unserialize($result->getCfgs()) ? unserialize($result->getCfgs()) : array();
            	$cfgs[$master] = $slave;
				$save = array();
				$save['cfgs'] = serialize($cfgs);
				$this->get('db_relation')->update($id, $save);
			}      
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
}