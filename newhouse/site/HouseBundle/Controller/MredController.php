<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月17日
*/
namespace HouseBundle\Controller;
        
/**
* 红包
* @author house
*/
class MredController extends Controller
{
        


    /**
    * 红包管理
    * house
    */
    public function mredmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 红包报名
    * house
    */
    public function mredenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}