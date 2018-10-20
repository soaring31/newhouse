<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月07日
*/
namespace HouseBundle\Controller;

/**
* 外站调用
* @author house
*/
class FragmentsController extends Controller
{
    /**
    * 外站新房调用
    * house
    */
    public function housesAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 通用外站调用模板
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的fragmentstpl方法
    * house
    */
    public function fragmentstplAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}