<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月14日
*/
namespace HouseBundle\Controller;
        
/**
* 相册
* @author house
*/
class MalbumController extends Controller
{
    /**
    * 管理相册
    * house
    */
    public function malbummanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}