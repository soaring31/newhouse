<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月26日
*/
namespace HouseBundle\Controller;

/**
* 会员认证列表
* @author house
*/
class MusercertController extends Controller
{
    /**
    * 会员认证列表
    * house
    */
    public function musercertmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}