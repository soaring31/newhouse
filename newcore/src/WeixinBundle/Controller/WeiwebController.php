<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace WeixinBundle\Controller;
        
/**
* 网站管理
* @author admina
*/
class WeiwebController extends Controller
{
    /**
    * 首页回复
    * admina
    */
    public function weiwebreplyAction()
    {
        $this->get('oauth.wechat_user')->getAccessToken();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 微分类
    * admina
    */
    public function weiwebclassAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 微模版
    * admina
    */
    public function weiwebtplAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 微菜单
    * admina
    */
    public function weiwebmenuAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 微场景
    * admina
    */
    public function weiwebliveAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 微预览
    * admina
    */
    public function weiwebpreviewAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}