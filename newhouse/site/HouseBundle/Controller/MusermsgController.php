<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月20日
*/
namespace HouseBundle\Controller;
        
/**
* 店铺留言
* @author house
*/
class MusermsgController extends Controller
{
        


    /**
    * 店铺留言
    * house
    */
    public function musermsglistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 店铺留言回复
    * house
    */
    public function musermsgreplyAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}