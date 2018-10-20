<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月07日
*/
namespace HouseBundle\Controller;

/**
* 问答举报
* @author house
*/
class MaskreportController extends Controller
{



    /**
    * 问答举报编辑
    * house
    */
    public function maskreportmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}