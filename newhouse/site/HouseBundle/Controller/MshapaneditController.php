<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月04日
*/
namespace HouseBundle\Controller;

/**
* 电子沙盘图片编辑
* @author house
*/
class MshapaneditController extends Controller
{
    /**
    * 编辑图片
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}