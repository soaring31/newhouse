<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月19日
*/
namespace WeixinBundle\Controller;
        
/**
* 微信-活动营销
* @author admina
*/
class EventController extends Controller
{
        


    /**
    * 优惠券
    * admina
    */
    public function couponAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 刮刮卡
    * admina
    */
    public function scratchAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 砸金蛋
    * admina
    */
    public function goldenEggAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}