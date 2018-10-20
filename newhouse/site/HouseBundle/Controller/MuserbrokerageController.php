<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月26日
*/
namespace HouseBundle\Controller;

/**
* 我的佣金管理
* @author house
*/
class MuserbrokerageController extends Controller
{



    /**
    * 实现的show方法
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 佣金提取列表
    * house
    */
    public function muserbrokeragelistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 佣金提取列表
    * house
    */
    public function muserextractAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 下级分销列表
    * house
    */
    public function musersubordinateAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 我的佣金
    * house
    */
    public function navblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}