<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月13日
*/
namespace HouseBundle\Controller;
        
/**
* 管理相册分类
* @author house
*/
class McatealbummanageController extends Controller
{
        


    /**
    * 修改
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 倒入数据
    * house
    */
    public function testAction()
    {
        
    }
}