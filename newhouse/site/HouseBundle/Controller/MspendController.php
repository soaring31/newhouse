<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月23日
*/
namespace HouseBundle\Controller;

/**
* 消费记录
* @author house
*/
class MspendController extends Controller
{



    /**
    * 消费记录列表
    * house
    */
    public function mspendmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}