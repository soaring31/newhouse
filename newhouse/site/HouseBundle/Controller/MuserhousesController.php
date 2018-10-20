<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月26日
*/
namespace HouseBundle\Controller;

/**
* 管理楼盘
* @author house
*/
class MuserhousesController extends Controller
{



    /**
    * 管理楼盘列表
    * house
    */
    public function muserhouseslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 管理楼盘区块
    * house
    */
    public function mhousesarcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}