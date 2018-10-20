<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年10月12日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class CreateappController extends Controller
{


    /**
    * 实现的createappmanage方法
    * house
    */
    public function createappmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}