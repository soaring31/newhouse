<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月15日
*/
namespace HouseBundle\Controller;
        
/**
* 特价房
* @author house
*/
class MspecialController extends Controller
{
    /**
    * 特价房
    * house
    */
    public function mspecialmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}