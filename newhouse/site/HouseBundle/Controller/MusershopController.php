<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月21日
*/
namespace HouseBundle\Controller;
        
/**
* 管理商铺
* @author house
*/
class MusershopController extends Controller
{
        

    /**
    * 管理商铺列表
    * house
    */
    public function musershoplistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}