<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月08日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class MwebtwController extends Controller
{
        
    /**
    * 实现的网站提问管理方法
    */
    public function 网站提问管理Action()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
        

    /**
    * 网站提问列表
    * house
    */
    public function mwebtwmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 网站提问回复
    * house
    */
    public function mreplymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}