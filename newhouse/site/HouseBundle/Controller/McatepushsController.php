<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月25日
*/
namespace HouseBundle\Controller;
        
/**
* 推送位管理
* @author house
*/
class McatepushsController extends Controller
{
        



    /**
    * 推送位列表
    * house
    */
    public function mcatepushslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}