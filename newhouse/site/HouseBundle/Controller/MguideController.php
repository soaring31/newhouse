<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月27日
*/
namespace HouseBundle\Controller;
        
/**
* 置业指南
* @author house
*/
class MguideController extends Controller
{
        


    /**
    * 置业指南
    * house
    */
    public function mguidemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}