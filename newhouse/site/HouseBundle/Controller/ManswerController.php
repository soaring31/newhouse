<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Controller;
        
/**
* 问答答案管理
* @author house
*/
class ManswerController extends Controller
{
        


    /**
    * 问答答案编辑
    * house
    */
    public function manswermanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}