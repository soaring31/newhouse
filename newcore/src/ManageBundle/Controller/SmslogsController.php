<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月01日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class SmslogsController extends Controller
{

    /**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = $this->get('request')->get('id', 0);
        
            if($id>0)
                $this->get('db.smslogs')->update($id, $_POST);
            else
                $this->get('db.smslogs')->add($_POST);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
	        
    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $this->get('db.smslogs')->delete($id);
        return $this->success('操作成功');
    }

    /**
    * 实现的delbatch方法
    * admina
    */
    public function delbatchAction()
    {
        $kid = $this->get('request')->get('kid', 0);
        $kid = intval($kid); if($kid<1) $kid = 3;
        //$this->get('db.smslogs')->delxxx($id); // 后续考虑用批量删除, 这里一条一条来删
        // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, $groupBy=null)  
        $lists = $this->get('db.smslogs')->findBy(array('create_time'=>array('lt'=>86400*30*$kid)));
        if(empty($lists['data'])){
            $msg = '无符合条件的数据！';
        }else{
            foreach ($lists['data'] as $value) {
                $this->get('db.smslogs')->delete($value->getId());
                $msg = '操作成功-删除'.$kid.'月前数据';
            }  
        }
        return $this->success($msg);
    }

	
}