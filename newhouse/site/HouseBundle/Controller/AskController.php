<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月07日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class AskController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    /**
    * 实现的index方法
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的list方法
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的detail方法
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的asklist方法
    * house
    */
    public function asklistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 回答页面
    * house
    */
    public function askdetailAction()
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
    * 回答
    * house
    */
    public function answerblockAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}