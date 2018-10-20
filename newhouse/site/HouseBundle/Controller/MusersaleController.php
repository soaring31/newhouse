<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月17日
*/
namespace HouseBundle\Controller;
        
/**
* 会员-二手房
* @author house
*/
class MusersaleController extends Controller
{
        


    /**
    * 会员-二手房
    * house
    */
    public function musersalelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发布二手房
    * house
    */
    public function musersaleinfoAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员二手房区块
    * house
    */
    public function msalearcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 房源图片
    * house
    */
    public function msalefymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}