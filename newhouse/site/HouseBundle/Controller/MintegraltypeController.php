<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月21日
*/
namespace HouseBundle\Controller;

/**
* 积分设置
* @author house
*/
class MintegraltypeController extends Controller
{



    /**
    * 积分管理
    * house
    */
    public function mintegraltypemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 积分兑换
    * house
    */
    public function mintegralcashAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}