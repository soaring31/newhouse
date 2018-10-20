<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月09日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author house
*/
class CategoryrelationController extends Controller
{
        

    /**
    * 类目关联管理
    * house
    */
    public function relationmanageAction()
    {      
        return $this->render($this->getBundleName(), $this->parameters);
    }

}