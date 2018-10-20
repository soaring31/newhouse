<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月28日
*/
namespace HouseBundle\Controller;

/**
* 缓存管理
* @author house
*/
class McachesController extends Controller
{



    /**
    * 缓存管理
    * house
    */
    public function mcachesmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 更新页面缓存
    * house
    */
    public function clearAction()
    {
        parent::cleanCace();
        return $this->success('操作成功');
    }


    /**
    * 更新缓存页面
    * house
    */
    public function mcachesupdateAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}