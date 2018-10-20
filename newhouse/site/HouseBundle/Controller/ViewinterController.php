<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月25日
*/
namespace HouseBundle\Controller;

/**
* 所有的ajax动作
* @author house
*/
class ViewinterController extends Controller
{



    /**
    * 实现的save方法
    * house
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
                    $info = $this->get('house.vote')->update($id, $_POST);
                }
            }else
                $info = $this->get('house.vote')->add($_POST);

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
                $this->get('house.vote')->delete($id);
            }
        }
        return $this->success('操作成功');
    }



    /**
    * 登录信息
    * house
    */
    public function logininfoAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 退出
    * house
    */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
    * 前台登录
    * house
    */
    public function viewloginAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    

    /**
    * 浏览记录
    * house
    */
    public function recordAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}