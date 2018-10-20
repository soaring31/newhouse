<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月03日
*/
namespace HouseBundle\Controller;
        
/**
* service
* @author house
*/
class ServiceController extends Controller
{
        


    /**
    * index.html.twig
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * list
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * ask
    * house
    */
    public function askAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * d
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的askdetail方法
    * house
    */
    public function askdetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 筛选
    * house
    */
    public function filterblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 网站提问
    * house
    */
    public function askblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}