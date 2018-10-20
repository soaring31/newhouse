<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年01月17日
*/
namespace HouseBundle\Controller;

/**
* 关注店铺
* @author house
*/
class MusershopsController extends Controller
{



    /**
    * 店铺收藏
    * house
    */
    public function musershopslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}