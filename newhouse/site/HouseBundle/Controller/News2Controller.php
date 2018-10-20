<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月12日
*/
namespace HouseBundle\Controller;

/**
* 资讯列表
* @author house
*/
class News2Controller extends Controller
{



    /**
    * 资讯列表
    * house
    */
    public function listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 资讯首页
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 资讯详情
    * house
    */
    public function detailAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}