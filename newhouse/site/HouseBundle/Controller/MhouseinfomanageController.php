<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年04月09日
*/
namespace HouseBundle\Controller;

/**
* 楼盘附属信息添加/修改
* @author house
*/
class MhouseinfomanageController extends Controller
{

    /**
    * 实现的show方法
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 楼盘附属信息保存
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
    }

    /**
    * 楼盘附属信息删除
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');
    }
}