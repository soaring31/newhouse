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
class GuideController extends Controller
{
    /**
    * 实现的index方法
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的detail方法
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * gl
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
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}