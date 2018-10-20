<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年06月22日
*/
namespace HouseBundle\Controller;

/**
* ajax数据
* @author house
*/
class AjaxdataController extends Controller
{



    /**
    * 导航
    * house
    */
    public function menuAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 表单
    * house
    */
    public function ajaxformAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 联动
    * house
    */
    public function selectbindAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 检查用户名
    * house
    */
    public function checkusersAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}