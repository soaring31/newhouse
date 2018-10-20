<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月15日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class HotkeywordsController extends Controller
{
        
    /**
    * 实现的hotkeywordsmanage方法
    */
    public function hotkeywordsmanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
        
}