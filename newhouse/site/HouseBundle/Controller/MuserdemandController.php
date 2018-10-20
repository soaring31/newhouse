<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月31日
*/
namespace HouseBundle\Controller;
        
/**
* 会员求租列表
* @author house
*/
class MuserdemandController extends Controller
{

    /**
    * 求购
    * house
    */
    public function musersaledemandAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 求租
    * house
    */
    public function muserrentdemandAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}