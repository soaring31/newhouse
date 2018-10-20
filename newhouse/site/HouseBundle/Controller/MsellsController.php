<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月06日
*/
namespace HouseBundle\Controller;

/**
* 楼盘分销提取
* @author house
*/
class MsellsController extends Controller
{



    /**
    * 楼盘分销列表
    * house
    */
    public function msellsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}