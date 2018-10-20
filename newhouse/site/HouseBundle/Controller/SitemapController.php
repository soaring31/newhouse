<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月10日
*/
namespace HouseBundle\Controller;

/**
* Sitemap
* @author house
*/
class SitemapController extends Controller
{



    /**
    * Sitemap地图
    * house
    */
    public function SitemapmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 绑定模型
    * house
    */
    public function sitemapbindAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}