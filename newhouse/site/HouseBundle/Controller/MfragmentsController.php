<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月07日
*/
namespace HouseBundle\Controller;

/**
* 外站调用
* @author house
*/
class MfragmentsController extends Controller
{



    /**
    * 外站调用
    * house
    */
    public function mfragmentsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 调用查看
    * house
    */
    public function transferAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 预览效果
    * house
    */
    public function previewAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 预览框架
    * house
    */
    public function previewiframeAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}