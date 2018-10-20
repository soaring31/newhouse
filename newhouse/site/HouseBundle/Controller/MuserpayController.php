<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月28日
*/
namespace HouseBundle\Controller;

/**
* 支付列表
* @author house
*/
class MuserpayController extends Controller
{
    /**
    * 支付记录
    * house
    */
    public function muserpaylistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}