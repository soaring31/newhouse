<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月13日
*/
namespace HouseBundle\Controller;
        
/**
* 团购
* @author house
*/
class MgroupbuyController extends Controller
{
        


    /**
    * 管理团购
    * house
    */
    public function mgroupbuymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 报名
    * house
    */
    public function mgroupordermanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}