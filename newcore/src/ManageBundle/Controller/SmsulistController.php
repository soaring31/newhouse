<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月08日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class SmsulistController extends Controller
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
            $ids = $this->get('request')->get('id', '');
        
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.user_attr')->update($id, $_POST);
                }
            }else
                $whr = array(
                    'uid' => $_POST['uid'],
                    //'name' => 'balance',
                    'type' => 'sms',
                );
                $_POST['type'] = 'sms';
                $row = $this->get('db.user_attr')->findOneBy($whr);
                if(!empty($row)){ $this->error('操作失败:此用户已经存在余额记录！'); }
                $this->get('db.user_attr')->add($_POST);
        
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
        $ids = $this->get('request')->get('id', '');
        
        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.user_attr')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}