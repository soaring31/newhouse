<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月11日
*/
namespace HouseBundle\Controller;
        
/**
* 专题管理
* @author house
*/
class MtopicController extends Controller
{
    /**
    * 专题
    * house
    */
    public function mtopicmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
 

    /**
    * 专题文档
    * house
    */
    public function mtopicarcmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 专题版块
    * house
    */
    public function mtopicblockmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 专题文档列表
    * house
    */
    public function mtopicarcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}