<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月10日
*/
namespace HouseBundle\Controller;
        
/**
* 需求信息
* @author house
*/
class MdemandController extends Controller
{
        


    /**
    * 管理需求
    * house
    */
    public function mdemandmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}