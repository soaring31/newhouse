<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月26日
*/
namespace HouseBundle\Controller;

/**
* 楼盘分销列表
* @author house
*/
class MsellController extends Controller
{



    /**
    * 特价房
    * house
    */
    public function msellmanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 已推荐
    * house
    */
    public function mrecommendAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}