<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月29日
*/
namespace HouseBundle\Controller;
        
/**
* 红包
* @author house
*/
class MenvelopeController extends Controller
{
        


    /**
    * 实现的menvelopemanage方法
    * house
    */
    public function menvelopemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}