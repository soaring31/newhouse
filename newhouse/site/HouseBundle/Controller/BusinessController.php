<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月12日
*/
namespace HouseBundle\Controller;
        
/**
* 商业
* @author house
*/
class BusinessController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    /**
    * 列表
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘详情
    * house
    */
    public function ddetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘动态
    * house
    */
    public function dnewsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘图库
    * house
    */
    public function dphotosAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘点评
    * house
    */
    public function dcommentAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘历史价格
    * house
    */
    public function dpricesAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 写字楼出租列表
    * house
    */
    public function xrentlistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 写字楼出售列表
    * house
    */
    public function xsalelistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 商铺出租列表
    * house
    */
    public function srentlistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 商铺出售列表
    * house
    */
    public function ssalelistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 商铺楼盘
    * house
    */
    public function slistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出售详情
    * house
    */
    public function sdetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租详情
    * house
    */
    public function rdetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情app
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function ddetailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出售详情app
    * house
    */
    public function sdetailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租详情
    * house
    */
    public function rdetailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}