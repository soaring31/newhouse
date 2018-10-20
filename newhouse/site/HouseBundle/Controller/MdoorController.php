<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月14日
*/
namespace HouseBundle\Controller;
        
/**
* 户型
* @author house
*/
class MdoorController extends Controller
{
        


    /**
    * 管理户型
    * house
    */
    public function mdoormanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}