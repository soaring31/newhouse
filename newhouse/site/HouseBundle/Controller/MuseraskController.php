<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月19日
*/
namespace HouseBundle\Controller;
        
/**
* 会员我的问答
* @author house
*/
class MuseraskController extends Controller
{
        


    /**
    * 我的提问
    * house
    */
    public function musersasklistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 我的回答
    * house
    */
    public function museranswerAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}