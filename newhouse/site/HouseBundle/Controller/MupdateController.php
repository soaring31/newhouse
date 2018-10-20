<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月31日
*/
namespace HouseBundle\Controller;
        
/**
* 系统升级
* @author house
*/
class MupdateController extends Controller
{
        


    /**
    * 系统升级页面
    * house
    */
    public function mupdateindexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}