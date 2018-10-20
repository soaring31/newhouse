<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月29日
*/
namespace HouseBundle\Controller;

/**
* 关注房源
* @author house
*/
class MuserhandController extends Controller
{



    /**
    * 关注房源列表
    * house
    */
    public function muserhandlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 头部
    * house
    */
    public function muserhandblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}