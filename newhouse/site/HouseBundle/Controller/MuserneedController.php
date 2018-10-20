<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月29日
*/
namespace HouseBundle\Controller;

/**
* 关注需求
* @author house
*/
class MuserneedController extends Controller
{



    /**
    * 关注需求列表
    * house
    */
    public function muserneedlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}