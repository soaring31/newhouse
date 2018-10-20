<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月20日
*/
namespace HouseBundle\Controller;
        
/**
* 周边配套
* @author house
*/
class MaroundController extends Controller
{
        


    /**
    * 周边管理
    * house
    */
    public function maroundmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}