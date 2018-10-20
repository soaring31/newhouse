<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月22日
*/
namespace HouseBundle\Controller;

/**
* 楼盘分销
* @author house
*/
class FenxiaoController extends Controller
{
    /**
    * 列表
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 赚佣金
    * house
    */
    public function earnAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘
    * house
    */
    public function fenxiao_housesAction()
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
    * 推荐购房
    * house
    */
    public function tuijianblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}