<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Controller;
        
/**
* 二手房三级菜单
* @author house
*/
class MsaleController extends Controller
{
    /**
    * 二手房三级菜单方法
    * house
    */
    public function msalemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 房源文档列表
    * house
    */
    public function msalearcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 房源图片管理
    * house
    */
    public function msalefymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 二手房委托
    * house
    */
    public function msaleconAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 房源委托
    * house
    */
    public function msalefywtAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 二手举报
    * house
    */
    public function msalereportAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 置顶
    * house
    */
    public function topAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 刷新
    * house
    */
    public function refreshAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 充值
    * house
    */
    public function chongzhiAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 手动刷新
    * house
    */
    public function handrefAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 委托房源
    * house
    */
    public function mentrustarcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 手动置顶
    * house
    */
    public function handtopAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 智能刷新
    * house
    */
    public function intelrefAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}