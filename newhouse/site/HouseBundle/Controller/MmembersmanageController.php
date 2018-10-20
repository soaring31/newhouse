<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月04日
*/
namespace HouseBundle\Controller;
        
/**
* 会员管理
* @author house
*/
class MmembersmanageController extends Controller
{
    /**
    * 编辑
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
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $user = $this->get('db.users');
                    $user->setMustCheck(false);
                    $user->update($id, $data);
                }
            }else{
                $user = $this->get('db.users');
                $user->setMustCheck(false);
                $user->add($data);
            }
        
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
        $ids = $this->get('request')->get('id', '');
        
        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.users')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

   

    /**
    * 重置密码
    * house
    */
    public function resetpwdAction()
    {
        return $this->success('重置成功！当前密码：'.$this->get('db.users')->resetpwd((int)$this->get('request')->get('_id', 0)));
        //return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 解除账号锁定
     * house
     */
    public function unlockAction()
    {
        $ids = array();
        $userId = $this->get('request')->get('_id', 0);
        $userInfo = $this->get('db.users')->findOneBy(array('id'=>$userId));
        $logList = $this->get('db.system_log')->findBy(array('name'=>'密码错误', 'operation'=>$userInfo->getUsername()));
        
        foreach($logList['data'] as $logItem)
        {
            $ids[] = $logItem->getId();
        }
        
        if($ids)
        {
            //$ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.system_log')->delete($id);
            }
        }
        return $this->success('解锁成功！');
    }
}