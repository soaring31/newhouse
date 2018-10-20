<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月16日
*/
namespace HouseBundle\Controller;

/**
* 域名管理
* @author house
*/
class MdomainsController extends Controller
{



    /**
    * 域名列表
    * house
    */
    public function mdomainsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 站内域名
    * house
    */
    public function mdomainsinnermanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}