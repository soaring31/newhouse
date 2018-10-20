<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月28日
*/
namespace HouseBundle\Controller;

/**
* 站内短信
* @author house
*/
class MusersmsController extends Controller
{



    /**
    * 站内短信列表
    * house
    */
    public function musersmslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发送站内短信
    * house
    */
    public function musersmssendAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}