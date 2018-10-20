<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月26日
*/
namespace HouseBundle\Controller;
        
/**
* 帮助中心
* @author house
*/
class MserviceController extends Controller
{
        


    /**
    * 实现的mservicemanage方法
    * house
    */
    public function mservicemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

   
}