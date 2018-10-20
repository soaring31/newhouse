<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月21日
*/
namespace HouseBundle\Controller;

/**
* 推荐菜单管理
* @author house
*/
class MnavController extends Controller
{



    /**
    * 推送菜单管理
    * house
    */
    public function mnavlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 推送菜单分类
    * house
    */
    public function mcatenavAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}