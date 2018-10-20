<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月12日
*/
namespace HouseBundle\Controller;

/**
* 经纪人出租
* @author house
*/
class MusercomrentController extends Controller
{



    /**
    * 经纪人出租列表
    * house
    */
    public function musercomrentlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}