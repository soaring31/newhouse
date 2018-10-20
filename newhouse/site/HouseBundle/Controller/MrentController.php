<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Controller;
        
/**
* 出租
* @author house
*/
class MrentController extends Controller
{
    /**
    * 实现的mrentmanage方法
    * house
    */
    public function mrentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的msalearc方法
    * house
    */
    public function msalearcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的msalefymanage方法
    * house
    */
    public function msalefymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租委托
    * house
    */
    public function mrentconAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}