<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月28日
*/
namespace HouseBundle\Controller;

/**
* 热门关键词
* @author house
*/
class MhotkeywordsController extends Controller
{



    /**
    * 管理热门关键词
    * house
    */
    public function mhotkeywordsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}