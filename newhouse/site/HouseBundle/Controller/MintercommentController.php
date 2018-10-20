<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月01日
*/
namespace HouseBundle\Controller;
        
/**
* 评论
* @author house
*/
class MintercommentController extends Controller
{
        


    /**
    * 评论管理
    * house
    */
    public function mintercommentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}