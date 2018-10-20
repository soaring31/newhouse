<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class DoorController extends Controller
{
    /**
    * list
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * detail
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 相似户型
    * house
    */
    public function huxingsAction()
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
    * 户型图集app
    * house
    */
    public function huxingsblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}