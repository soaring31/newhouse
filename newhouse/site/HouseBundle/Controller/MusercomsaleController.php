<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月11日
*/
namespace HouseBundle\Controller;

/**
* 经纪人二手房
* @author house
*/
class MusercomsaleController extends Controller
{



    /**
    * 经纪公司二手房列表
    * house
    */
    public function musercomsalelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}