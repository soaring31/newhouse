<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月30日
*/
namespace HouseBundle\Controller;

/**
* 关注问答
* @author house
*/
class MuserasksController extends Controller
{



    /**
    * 关注问答列表
    * house
    */
    public function museraskslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}