<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月19日
*/
namespace HouseBundle\Controller;
        
/**
* 说明文档
* @author house
*/
class CmsdocController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    /**
    * cmsdoc
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * tp2
    * house
    */
    public function topicdetail2Action()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}