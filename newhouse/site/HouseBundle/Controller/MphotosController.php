<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月14日
*/
namespace HouseBundle\Controller;
        
/**
* 图库
* @author house
*/
class MphotosController extends Controller
{
        


    /**
    * 图库管理
    * house
    */
    public function mphotosmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}