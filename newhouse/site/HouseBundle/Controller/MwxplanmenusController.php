<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月15日
*/
namespace HouseBundle\Controller;

/**
* 微信菜单方案
* @author house
*/
class MwxplanmenusController extends Controller
{



    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        // 处理菜单项目
        if($this->get('request')->getMethod() == "POST")
        {
            $aid = $this->get('request')->get('aid',0);
            $fmdata = $this->get('request')->get('fmdata', array());
            $this->get('db.wxplanmenus')->mulSave($fmdata,$aid);            
            
            return $this->success('操作成功');
        }
    }
    
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.wxplanmenus')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}