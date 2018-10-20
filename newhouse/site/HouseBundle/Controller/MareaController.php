<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月24日
*/
namespace HouseBundle\Controller;
        
/**
* 区域
* @author house
*/
class MareaController extends Controller
{
        

    /**
    * 管理区域
    * house
    */
    public function mareamanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的mareareset方法
    * house
    */
    public function marearesetAction()
    {
        $this->get('db.area')->createBinaryNode();
        $this->parameters['data'] = '操作成功';
        return $this->render($this->getBundleName(), $this->parameters);
    }
}