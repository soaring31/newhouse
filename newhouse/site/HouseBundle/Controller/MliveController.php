<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月15日
*/
namespace HouseBundle\Controller;
        
/**
* 直播
* @author house
*/
class MliveController extends Controller
{
        


    /**
    * 直播编辑
    * house
    */
    public function mlivemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 直播信息编辑
    * house
    */
    public function mlivenewsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}