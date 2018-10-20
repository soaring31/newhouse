<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月06日
*/
namespace HouseBundle\Controller;

/**
* 团购修改
* @author house
*/
class MgroupbuysmanageController extends Controller
{



    /**
    * sh
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}