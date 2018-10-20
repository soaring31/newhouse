<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月12日
*/
namespace HouseBundle\Controller;

/**
* 房价走势
* @author house
*/
class MpricesController extends Controller
{
	

    /**
    * 实现的mpricesmanage方法
    * house
    */
    public function mpricesmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}