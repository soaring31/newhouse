<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月15日
*/
namespace HouseBundle\Controller;
        
/**
* 楼盘意向
* @author house
*/
class MinterintentController extends Controller
{
        


    /**
    * 管理意向
    * house
    */
    public function minterintentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}