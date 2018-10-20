<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月12日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina 
*/
class BadkeywordsController extends Controller
{

    /**
    * 不良词列表
    * admina
    */
    public function badkeywordsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
}