<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace HouseBundle\Controller;

/**
* 整站首页
* @author house
*/
class IndexController extends Controller
{
    /**
    * 首页
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 测试app
    * house
    */
    public function appdemoAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块
    * house
    */
    public function blockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 头部
    * house
    */
    public function headerblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}