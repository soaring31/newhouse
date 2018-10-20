<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月09日
*/
namespace HouseBundle\Controller;

/**
* 工作统计
* @author house
*/
class MworkcountsController extends Controller
{
    /**
    * 工作统计
    * house
    */
    public function mworkcountsmanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

   

    /**
    * 绩效统计列表
    * house
    */
    public function mworklistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}