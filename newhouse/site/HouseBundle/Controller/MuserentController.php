<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月18日
*/
namespace HouseBundle\Controller;
        
/**
* 
* @author house
*/
class MuserentController extends Controller
{
        

    /**
    * 租房列表
    * house
    */
    public function muserentlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}