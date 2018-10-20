<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月29日
*/
namespace HouseBundle\Controller;

/**
* 省市主题
* @author house
*/
class MareaconfigController extends Controller
{
    /**
    * 城市主题
    * house
    */
    public function mareaconfigmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}