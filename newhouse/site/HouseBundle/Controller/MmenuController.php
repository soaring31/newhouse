<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月24日
*/
namespace HouseBundle\Controller;
        
/**
* 后台菜单
* @author house
*/
class MmenuController extends Controller
{
        


    /**
    * 菜单管理
    * house
    */
    public function mmenulistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员中心菜单
    * house
    */
    public function membermenuAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}