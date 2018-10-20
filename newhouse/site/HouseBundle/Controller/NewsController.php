<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月03日
*/
namespace HouseBundle\Controller;
        
/**
* 资讯
* @author house
*/
class NewsController extends Controller
{


    /**
    * 资讯首页
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * list
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的detail方法
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 资讯列表
    * house
    */
    public function newslistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 筛选
    * house
    */
    public function filterblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 评论
    * house
    */
    public function commentsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}