<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月07日
*/
namespace HouseBundle\Controller;
        
/**
* 视频
* @author house
*/
class MvideoController extends Controller
{
        


    /**
    * 视频管理
    * house
    */
    public function mvideomanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}