<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年01月11日
*/
namespace HouseBundle\Controller;

/**
* 地区管理员添加角色
* @author house
*/
class MauthorityaccessareaController extends Controller
{
    /**
    * 添加地区管理员角色
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
            
            $uids = $this->get('request')->get('uid', '');
            
            $data = $this->get('request')->request->all();
            
            unset($data['csrf_token']);

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.auth_access_area')->update($id, $data);
                }
            }elseif ($uids) {
                
                $uids = explode(',', $uids);
                foreach ($uids as $uid)
                {
                    $data['uid'] = $uid;
                    $info = $this->get('db.auth_access_area')->add($data);
                }
            }else
                $info = $this->get('db.auth_access_area')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
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
                $this->get('db.auth_access_area')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}