<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月31日
*/
namespace HouseBundle\Controller;
        
/**
* 会员收藏
* @author house
*/
class MusercollectController extends Controller
{
    /**
    * 会员收藏
    * house
    */
    public function musercollectlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}