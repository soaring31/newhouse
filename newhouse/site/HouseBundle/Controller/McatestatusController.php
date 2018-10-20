<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月28日
*/
namespace HouseBundle\Controller;
        
/**
* 销售状态
* @author house
*/
class McatestatusController extends Controller
{
        


    /**
    * 实现的mcatestatusmanage方法
    * house
    */
    public function mcatestatusmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}