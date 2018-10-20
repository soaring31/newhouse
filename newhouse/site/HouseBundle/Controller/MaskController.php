<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月03日
*/
namespace HouseBundle\Controller;
        
/**
* 问答管理
* @author house
*/
class MaskController extends Controller
{
        


    /**
    * 问答列表
    * house
    */
    public function maskmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}