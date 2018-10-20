<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月09日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class AreachangeController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }

    /**
    * 地区模板
    * house
    */
    public function mchangeAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 地区首页
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的indexblock方法
    * house
    */
    public function indexblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}