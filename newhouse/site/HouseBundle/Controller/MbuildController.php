<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月30日
*/
namespace HouseBundle\Controller;
        
/**
* 楼盘楼栋管理
* @author house
*/
class MbuildController extends Controller
{
        


    /**
    * 楼栋管理
    * house
    */
    public function mbuildmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}