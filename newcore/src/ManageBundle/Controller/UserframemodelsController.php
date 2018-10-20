<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月18日
*/
namespace ManageBundle\Controller;

/**
* 会员模型管理
* @author admin
*/
class UserframemodelsController extends Controller
{      
    /**
    * 添加会员模型twig
    * admin
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 实现的sava方法
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');

            if($id>0)
                $this->get('db.user_models')->update($id, $_POST);
            else
                $this->get('db.user_models')->add($_POST);
        
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
    
    /**
     * 实现的delete方法
     */
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id');
        $this->get('db.user_models')->delete($id);
        return $this->success('操作成功');
    }
}