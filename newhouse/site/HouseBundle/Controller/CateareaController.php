<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月18日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class CateareaController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    /**
    * 实现的cateareamanage方法
    * house
    */
    public function cateareamanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}