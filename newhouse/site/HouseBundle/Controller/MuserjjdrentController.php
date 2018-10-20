<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月20日
*/
namespace HouseBundle\Controller;

/**
* 经纪人出租房源
* @author house
*/
class MuserjjdrentController extends Controller
{



    /**
    * j经纪人出租房源
    * house
    */
    public function muserjjdrentlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}