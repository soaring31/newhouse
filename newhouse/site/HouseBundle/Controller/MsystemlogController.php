<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月29日
*/
namespace HouseBundle\Controller;

/**
* 操作记录
* @author house
*/
class MsystemlogController extends Controller
{
    /**
    * 操作日志管理
    * house
    */
    public function msystemlogmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}