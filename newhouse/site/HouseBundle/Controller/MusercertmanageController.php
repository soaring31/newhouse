<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月26日
*/
namespace HouseBundle\Controller;

/**
* 会员认证列表
* @author house
*/
class MusercertmanageController extends Controller
{
    /**
    * 会员认证列表添加
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
            $aid = $this->get('request')->get('aid', '');

            if ($aid)
            {
                $this->get('request')->setMethod('DELETE');
                $this->get('house.user_cert')->delete($aid);
                return $this->success('解绑成功');
            } elseif($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('house.user_cert')->update($id, $data);
                }
            }else
                $info = $this->get('house.user_cert')->add($data);

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
                $this->get('house.user_cert')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 移除绑定
    * house
    */
    public function removeAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}