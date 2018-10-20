<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月13日
*/
namespace HouseBundle\Controller;

/**
* 手机客户端
* @author house
*/
class DownloadController extends Controller
{

    /**
    * 实现的return方法
    */
    public function returnAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
   

    /**
    * 手机客户端
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}