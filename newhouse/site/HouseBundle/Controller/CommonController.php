<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace HouseBundle\Controller;
        
/**
* common
* @author house
*/
class CommonController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }

    /**
    * header
    * house
    */
    public function headerAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * footer
    * house
    */
    public function footerAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * loginbar
    * house
    */
    public function loginAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * webinfo
    * house
    */
    public function webinfoAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * search
    * house
    */
    public function topsearchAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * c
    * house
    */
    public function compareAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * filter
    * house
    */
    public function filterAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * app导航
    * house
    */
    public function navblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 列表基模型
    * house
    */
    public function listblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 地图app
    * house
    */
    public function mapblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的headersearchblock方法
    * house
    */
    public function headersearchblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的filtersearch方法
    * house
    */
    public function filtersearchAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 多级过滤条件
    * house
    */
    public function filterlevelAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘对比
    * house
    */
    public function contrastAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 商圈
    * house
    */
    public function circleAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 地铁站
    * house
    */
    public function metroAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 表单
    * house
    */
    public function formsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}