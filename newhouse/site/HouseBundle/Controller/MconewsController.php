<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月10日
*/
namespace HouseBundle\Controller;
        
/**
* 公司动态管理
* @author house
*/
class MconewsController extends Controller
{
        


    /**
    * 实现的mconewsmanage方法
    * house
    */
    public function mconewsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}