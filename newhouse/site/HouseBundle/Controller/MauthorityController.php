<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年01月11日
*/
namespace HouseBundle\Controller;

/**
* 地区管理员
* @author house
*/
class MauthorityController extends Controller
{




    /**
    * 地区管理员列表
    * house
    */
    public function mauthorityruleareaAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 地区成员列表
    * house
    */
    public function mauthorityaccessareaAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 加载分站管理员
    * house
    */
    public function mmemloadAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}