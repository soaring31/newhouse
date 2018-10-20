<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年07月13日
*/
namespace HouseBundle\Controller;

/**
* 主站设置
* @author house
*/
class MastersetController extends Controller
{



    /**
    * 实现的dddd方法
    * house
    */
    public function ddddAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}