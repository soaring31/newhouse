<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月10日
*/
namespace HouseBundle\Controller;

/**
* 绑定多个表单
* @author house
*/
class BundlebindformsController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }

    /**
    * base模板
    * house
    */
    public function bundlebaseAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘订阅
    * house
    */
    public function interenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

   

    /**
    * 团购
    * house
    */
    public function groupenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 看房团
    * house
    */
    public function kftenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 意向报名
    * house
    */
    public function yixiangenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 二手,出租
    * house
    */
    public function saleactionAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 分销
    * house
    */
    public function fxearnenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 前台评论
    * house
    */
    public function commentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 我要提问
    * house
    */
    public function askenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 问答评论
    * house
    */
    public function askcommentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘评论
    * house
    */
    public function lpcommentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 服务提问评论
    * house
    */
    public function servicecommentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 店铺留言
    * house
    */
    public function lycommentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘印象
    * house
    */
    public function yinxiangAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 红包
    * house
    */
    public function hongbaoAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 红包报名
    * house
    */
    public function hongbaoenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 找回密码
    * house
    */
    public function resetpwdAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 修改密码
    * house
    */
    public function muserpwdAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 注册
    * house
    */
    public function registerAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 注册
    * house
    */
    public function loginAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘对比
    * house
    */
    public function comparisonAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 通用报名
    * house
    */
    public function commonenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 佣金提取
    * house
    */
    public function wagesenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

     /**
    * 表单
    * house
    */
    public function ajaxformAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 检索条件
    * house
    */
    public function filterAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 前台区块内容接口
    * house
    */
    public function ajaxAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}