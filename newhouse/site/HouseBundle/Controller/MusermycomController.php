<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月10日
*/
namespace HouseBundle\Controller;

/**
* 我的经纪公司
* @author house
*/
class MusermycomController extends Controller
{



    /**
    * 经纪公司列表
    * house
    */
    public function musermycomlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}