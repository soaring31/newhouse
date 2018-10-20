<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月19日
*/
namespace WeixinBundle\Controller;
        
/**
* 
* @author admina
*/
class ShakearoundController extends Controller
{
        


    /**
    * 设备管理
    * admina
    */
    public function deviceManageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 页面管理
    * admina
    */
    public function pageManageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 数据统计
    * admina
    */
    public function dataAnalysisAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}