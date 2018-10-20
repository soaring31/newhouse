<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月23日
*/
namespace HouseBundle\Controller;
        
/**
* 资讯
* @author house
*/
class MnewsController extends Controller
{
        


    /**
    * 栏目管理
    * house
    */
    public function mnewsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    
    /**
    * 侧边栏目
    * house
    */
    public function categoryAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 资讯楼盘
    * house
    */
    public function mnewshousesAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘信息查看
    * house
    */
    public function mnewshousesinfoAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}