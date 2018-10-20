<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月27日
*/
namespace HouseBundle\Controller;
        
/**
* 管理地铁
* @author house
*/
class McatemetroController extends Controller
{
  

    /**
    * 管理地铁
    * house
    */
    public function mcatemetromanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}