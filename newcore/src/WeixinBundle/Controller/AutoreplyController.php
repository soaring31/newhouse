<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月20日
*/
namespace WeixinBundle\Controller;
        
/**
* 
* @author admina
*/
class AutoreplyController extends Controller
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

            $_POST['token'] = $this->get('request')->getSession()->get('wxtoken');
            if(empty($_POST['token']))
                return $this->error('没有选择公众号！操作失败！');

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.wxkeyword')->update($id, $_POST);
                }
            }else
                $this->get('db.wxkeyword')->add($_POST);
        
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
                $this->get('db.wxkeyword')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}