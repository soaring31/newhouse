<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月17日13798701708
*/
namespace HouseBundle\Controller;
        
/**
* 看房团管理
* @author house
*/
class MshowingsController extends Controller
{
    /**
    * 实现的mshowingsmanage方法
    * house
    */
    public function mshowingsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的mshowingsnewsmanage方法
    * house
    */
    public function mshowingsnewsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}