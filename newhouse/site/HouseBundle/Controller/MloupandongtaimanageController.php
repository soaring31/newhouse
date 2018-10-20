<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年04月09日
*/
namespace HouseBundle\Controller;

/**
* 楼盘动态
* @author house
*/
class MloupandongtaimanageController extends Controller
{



    /**
    * 添加楼房动态展示页面
    * house
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 保存楼盘动态
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")                {                    $ids = $this->get('request')->get('id', '');                    $data = $this->get('request')->request->all();                    if($ids)                    {                        $ids = explode(',', $ids);                        foreach($ids as $id)                        {                            $this->get('house.loupandongtai')->update($id, $data);                        }                    }else                        $this->get('house.loupandongtai')->add($data);                                    return $this->success('操作成功');                }                                return $this->error('操作失败');
    }

    /**
    * 删除楼盘动态
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');                                if($ids)                {                    $ids = explode(',', $ids);                    foreach($ids as $id)                    {                        $this->get('house.loupandongtai')->delete($id);                    }                }                return $this->success('操作成功');
    }
}