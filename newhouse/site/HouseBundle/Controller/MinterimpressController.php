<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月29日
*/
namespace HouseBundle\Controller;
        
/**
* 楼盘印象
* @author house
*/
class MinterimpressController extends Controller
{
        


    /**
    * 管理印象
    * house
    */
    public function MinterimpressmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}