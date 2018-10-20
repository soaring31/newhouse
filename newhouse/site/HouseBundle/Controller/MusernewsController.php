<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月26日
*/
namespace HouseBundle\Controller;

/**
* 公司动态
* @author house
*/
class MusernewsController extends Controller
{



    /**
    * 会员公司动态列表
    * house
    */
    public function musernewslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}