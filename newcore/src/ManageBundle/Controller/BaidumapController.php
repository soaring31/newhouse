<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月07日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class BaidumapController extends Controller
{
    /**
    * 实现的index方法
    * admina
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
}