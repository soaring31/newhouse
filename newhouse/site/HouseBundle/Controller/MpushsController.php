<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月25日
*/
namespace HouseBundle\Controller;
        
/**
* 推送管理
* @author house
*/
class MpushsController extends Controller
{
    /**
    * 推送列表
    * house
    */
    public function mpushslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}