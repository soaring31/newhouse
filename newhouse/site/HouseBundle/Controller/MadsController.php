<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月28日
*/
namespace HouseBundle\Controller;

/**
* 广告管理
* @author house
*/
class MadsController extends Controller
{
    /**
    * 广告管理列表
    * house
    */
    public function MadslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 广告分类管理
    * house
    */
    public function mcateadsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}