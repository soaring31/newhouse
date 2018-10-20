<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月28日
*/
namespace HouseBundle\Controller;
        
/**
* 地铁
* @author house
*/
class McatelineController extends Controller
{
        


    /**
    * 地铁
    * house
    */
    public function mcatelinemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}