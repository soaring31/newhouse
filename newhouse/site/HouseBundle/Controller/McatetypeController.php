<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月28日
*/
namespace HouseBundle\Controller;
        
/**
* 物业类型
* @author house
*/
class McatetypeController extends Controller
{
        


    /**
    * 物业类型
    * house
    */
    public function mcatetypemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}