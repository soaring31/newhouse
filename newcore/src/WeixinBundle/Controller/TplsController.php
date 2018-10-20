<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace WeixinBundle\Controller;
        
/**
* 模版管理
* @author admina
*/
class TplsController extends Controller
{
        


    /**
    * 模版管理
    * admina
    */
    public function tplsindexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 列表页风格
    * admina
    */
    public function tplslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情页风格
    * admina
    */
    public function tplsdetailsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}