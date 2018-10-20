<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月27日
*/
namespace HouseBundle\Controller;
        
/**
* 管理商圈
* @author house
*/
class McatecircleController extends Controller
{
    /**
    * 管理商圈
    * house
    */
    public function mcatecirclemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }   

}