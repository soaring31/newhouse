<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月08日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class McatesaleController extends Controller
{
        


    /**
    * 实现的mcatesalemanage方法
    * house
    */
    public function mcatesalemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}