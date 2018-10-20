<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月23日
*/
namespace HouseBundle\Controller;

/**
* 统计
* @author house
*/
class IgetcountsController extends Controller
{
    /**
    * 首页
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 点击统计
    * house
    */
    public function detailcountsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}