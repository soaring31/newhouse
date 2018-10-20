<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月19日
*/
namespace WeixinBundle\Controller;
        
/**
* 推广渠道
* @author admina
*/
class WxpipelineController extends Controller
{
        


    /**
    * 推广海报
    * admina
    */
    public function posterAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 渠道二维码
    * admina
    */
    public function qrcodeAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 二维码扫描分析
    * admina
    */
    public function qrcodeSweepAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}