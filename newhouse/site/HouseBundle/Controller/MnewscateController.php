<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月24日
*/
namespace HouseBundle\Controller;
        
/**
* 类目管理
* @author house
*/
class MnewscateController extends Controller
{
        


    /**
    * 栏目分类
    * house
    */
    public function mnewscatelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 分类方案列表
    * house
    */
    public function mcatemodelsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}