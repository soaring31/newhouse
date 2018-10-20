<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月20日
*/
namespace HouseBundle\Controller;

/**
* 经纪人二手房源
* @author house
*/
class MuserjjdsaleController extends Controller
{



    /**
    * 经纪人二手房源列表
    * house
    */
    public function muserjjdsalelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}