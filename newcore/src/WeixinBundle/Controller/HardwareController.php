<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月19日
*/
namespace WeixinBundle\Controller;
        
/**
* WIFI&打印
* @author admina
*/
class HardwareController extends Controller
{
        


    /**
    * WIFI设备管理
    * admina
    */
    public function wifiDeviceAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * WIFI设置
    * admina
    */
    public function wifiSetAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 商家设置
    * admina
    */
    public function memSetAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}