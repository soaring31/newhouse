<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月21日
*/
namespace ManageBundle\Controller;

/**
*
* @author admina
*/
class SitemapController extends Controller
{


    /**
    * 菜单一
    * admina
    */
    public function sitemapmanageAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的sitemapbind方法
    * admina
    */
    public function sitemapbindAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }
}