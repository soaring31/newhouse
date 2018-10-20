<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月28日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class VoteController extends Controller
{



    /**
    * 投票管理
    * house
    */
    public function VotemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的votelist方法
    * house
    */
    public function votelistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}