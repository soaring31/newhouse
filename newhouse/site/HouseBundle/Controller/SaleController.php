<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月10日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class SaleController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    /**
    * list
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * detail
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * xuequ
    * house
    */
    public function xlistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * xuequ
    * house
    */
    public function xdetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * list
    * house
    */
    public function xqlistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * xiaoqu
    * 小区详情首页
    */
    public function qdetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * xiaoqu
    * 小区详情二手房
    */
    public function qdmaiAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * xiaoqu
    * 小区详情出租
    */
    public function qdzhuAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 小区详情图库
    * house
    */
    public function qdphotosAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 小区详情户型图
    * house
    */
    public function qdplanintroAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情模块
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 意向
    * house
    */
    public function yixiangblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 学区详情app
    * house
    */
    public function xdetailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 小区详情
    * house
    */
    public function qdetailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的qdcomment方法
    * house
    */
    public function qdcommentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}