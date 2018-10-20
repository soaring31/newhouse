<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月20日
*/
namespace WeixinBundle\Controller;
        
/**
* 
* @author admina
*/
class CouponController extends Controller
{
        


    /**
    * 新增优惠券
    * admina
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}