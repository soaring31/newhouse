<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年04月07日
*/
namespace HouseBundle\Controller;

/**
* 管理楼盘动态
* @author house
*/
class MtrendsController extends Controller
{

    /**
    * 实现的mtrendsmanage方法
    */
    public function mtrendsmanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

}