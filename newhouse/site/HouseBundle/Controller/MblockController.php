<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月19日
*/
namespace HouseBundle\Controller;
        
/**
* 区块
* @author house
*/
class MblockController extends Controller
{
        


    /**
    * 区块
    * house
    */
    public function mblockmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块文档列表
    * house
    */
    public function mblockarcmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 加载文档
    * house
    */
    public function loadarcsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块团购管理
    * house
    */
    public function mgroupbuymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块相册管理
    * house
    */
    public function malbummanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块户型管理
    * house
    */
    public function mdoormanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块意向管理
    * house
    */
    public function minterintentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块红包管理
    * house
    */
    public function mredmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块特价房管理
    * house
    */
    public function mbargainmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块印象管理
    * house
    */
    public function minterimpressmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块楼栋管理
    * house
    */
    public function mbuildmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块点评管理
    * house
    */
    public function mcommentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}