<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class ToolsController extends Controller
{
        


    /**
    * index
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 主页
    * house
    */
    public function indexblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}