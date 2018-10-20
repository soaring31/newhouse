<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月28日
*/
namespace HouseBundle\Controller;
        
/**
* 合辑
* @author house
*/
class McollectionController extends Controller
{
        


    /**
    * 管理合集
    * house
    */
    public function mcollectionmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

   
}