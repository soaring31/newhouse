<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月12日
*/
namespace HouseBundle\Controller;

/**
* 经纪人管理
* @author house
*/
class MuseragentController extends Controller
{



    /**
    * 经纪人管理
    * house
    */
    public function museragentlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 申请列表
    * house
    */
    public function museragentlistncAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}