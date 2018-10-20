<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月21日
*/
namespace HouseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
        
/**
* 详情页面
* @author house
*/
class DetailController extends Controller
{
    /**
    * 实现的index方法
    * house
    */
    public function detailAction(Request $request, $id)
    {
        if(empty($id))
            die();
        
        $detail = $this->get('core.common')->decode($id);
        $detail = explode(',',$detail);
        $id = array_pop($detail);
        $controName = ucfirst(array_pop($detail));
        $bundleName = count($detail)>0?ucfirst(array_pop($detail))."Bundle":$this->get('core.common')->getBundleName();
        
        $url = $bundleName.":";
        $url .= $controName.":detail";

        //重定向路径
        return $this->forward($url, array(), array('id'=>$id));
    }
}