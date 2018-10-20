<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月12日
*/
namespace HouseBundle\Controller;
        
/**
* 类系
* @author house
*/
class MclassifysController extends Controller
{
        


    /**
    * 类系
    * house
    */
    public function mclassifysmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}