<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月28日
*/
namespace HouseBundle\Controller;

/**
* 不良词
* @author house
*/
class MbadwordsController extends Controller
{



    /**
    * 不良词管理
    * house
    */
    public function mbadwordsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}