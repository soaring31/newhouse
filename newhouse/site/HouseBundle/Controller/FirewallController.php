<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月22日
*/
namespace HouseBundle\Controller;

/**
* 防火墙设置
* @author house
*/
class FirewallController extends Controller
{


    /**
    * 防火墙管理
    * house
    */
    public function firewallsetAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}