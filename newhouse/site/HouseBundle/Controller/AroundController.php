<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月13日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class AroundController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }

    /**
    * 周边详情
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的detailblock方法
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}