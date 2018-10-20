<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月06日
*/
namespace HouseBundle\Controller;

/**
* 团购
* @author house
*/
class MgroupbuysController extends Controller
{



    /**
    * 团购管理
    * house
    */
    public function mgroupbuysmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}