<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年01月19日
*/
namespace CoreBundle\Controller;

/**
* 测试控制器
* @author admin
*/
class TestFunController extends Controller
{
    /**
    * 实现的index方法
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}