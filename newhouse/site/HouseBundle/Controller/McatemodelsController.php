<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月10日
*/
namespace HouseBundle\Controller;

/**
* 分类方案
* @author house
*/
class McatemodelsController extends Controller
{



    /**
    * 分类方案列表
    * house
    */
    public function mcatemodelsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}