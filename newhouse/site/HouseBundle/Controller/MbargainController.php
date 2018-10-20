<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月03日
*/
namespace HouseBundle\Controller;
        
/**
* 特价房
* @author house
*/
class MbargainController extends Controller
{
        


    /**
    * 管理特价房
    * house
    */
    public function mbargainmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}