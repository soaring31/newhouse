<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace WeixinBundle\Controller;
        
/**
* 基本功能
* @author admina
*/
class BasefunController extends Controller
{

    /**
    * 群发消息
    * admina
    */
    public function messageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 商家连锁
    * admina
    */
    public function businessAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 素材管理
    * admina
    */
    public function materialAction()
    {
        
        $this->parameters['type'] = $this->get('request')->get('type', '');
        $this->parameters['material']['types'] = array('' => '全部', 'image' => '图片', 'video' => '视频', 'voice' => '语音');

        return $this->render($this->getBundleName(), $this->parameters);

    }

    /**
    * 图文素材管理
    * admina
    */
    public function materialnewsAction()
    {   
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 粉丝管理
    * admina
    */
    public function fansAction()
    {
        $this->parameters['tags'] = $this->get('oauth.weixin_user')->getTags();
        $this->parameters['tagname'] = $this->get('request')->get('tagname', '');
        $this->parameters['nickname'] = $this->get('request')->get('nickname', '');
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 关键字管理
    * admina
    */
    public function autoreplyAction()
    {
        $this->parameters['keyword'] = $this->get('request')->get('keyword', '');
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 默认设置
    * admina
    */
    public function defaultreplyAction()
    {
        $token = $this->get('request')->getSession()->get('wxtoken');
        if(!isset($token))
            $this->error('错误！没有正确的微信公众号。');
        
        $this->parameters['info'] = $this->get('db.wxdefault')->findOneBy(array('token'=>$token, 'findType'=>1));

        if($this->get('request')->getMethod() == "POST")
        {
            $_POST['token'] = $token;
            if(empty($this->parameters['info']))
                $this->get('db.wxdefault')->add($_POST);
            else
                $this->get('db.wxdefault')->update($this->parameters['info']['id'], $_POST);

            return $this->success('操作成功');
        }
        return $this->render($this->getBundleName(), $this->parameters);
    }
}