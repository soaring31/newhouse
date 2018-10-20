<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月28日
*/
namespace WeixinBundle\Controller;
        
/**
* 门店管理
* @author admina
*/
class ShopController extends Controller
{
        


    /**
    * 添加编辑门店
    * admina
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的shopcat方法
    * admina
    */
    public function shopcatAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}