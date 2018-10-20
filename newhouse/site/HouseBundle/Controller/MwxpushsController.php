<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月06日
*/
namespace HouseBundle\Controller;

/**
* 微信推送
* @author house
*/
class MwxpushsController extends Controller
{



    /**
    * 管理推送
    * house
    */
    public function mwxpushsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}