<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月28日
*/
namespace HouseBundle\Controller;

/**
* 后台ajax动作
* @author house
*/
class MajaxactionController extends Controller
{
    /**
    * 小区
    * house
    */
    public function xiaoquAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 关键字
    * house
    */
    public function keywordsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}