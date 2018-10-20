<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年01月19日
*/
namespace HouseBundle\Controller;

/**
* 分销推广链接
* @author house
*/
class MuserselllinkController extends Controller
{



    /**
    * 分销推广链接列表
    * house
    */
    public function muserselllinklistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
   

    /**
    * 邀请下线明细
    * house
    */
    public function musersellsubordinateAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}