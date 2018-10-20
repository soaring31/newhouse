<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月26日
*/
namespace WeixinBundle\Controller;
        
/**
* 
* @author admina
*/
class WxpoiController extends Controller
{
        


    /**
    * 商户信息展示
    * admina
    */
    public function businessBaseinfoShowAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的businessBaseinfo方法，门店基本信息
    * admina
    */
    public function businessBaseinfoAction()
    {
        $token = $this->get('request')->getSession()->get('wxtoken');
        if(!isset($token))
            $this->error('错误！没有正确的微信公众号。');

        $row = $this->get('db.wxpoi')->findOneBy(array('token'=>$token, 'findType'=>1));
        $this->get('request')->query->add(array('id'=>$row['id']));
        //$fff = $this->get('request')->query;
        //dump($fff);die;

        // 保存
        if($this->get('request')->getMethod() == "POST")
        {
            $_POST['token'] = $token;
            if(empty($this->parameters['info']))
                $this->get('db.wxpoi')->add($_POST);
            else
                $this->get('db.wxpoi')->update($this->parameters['info']['id'], $_POST);

            return $this->success('操作成功');
        }
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
                    $this->get('db.wxpoi')->update($id, $_POST);
                }
            }else
                $this->get('db.wxpoi')->add($_POST);
        
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
                $this->get('db.wxpoi')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 门店管理
    * admina
    */
    public function shopAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 门店行业管理
    * admina
    */
    public function shopcatAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}