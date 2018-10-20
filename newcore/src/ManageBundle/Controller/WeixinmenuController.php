<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月17日
*/
namespace ManageBundle\Controller;

/**
* 
* @author admina
*/
class WeixinmenuController extends Controller
{
    
    /**
    * 实现的show方法
    * admina
    */
    /*
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }*/

    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
        $appid = $this->get('request')->get('appid', '');

        if(empty($appid))
            return $this->error('操作失败：Appid为空。');

        // 处理菜单项目
        if($this->get('request')->getMethod() == "POST")
        {
            $fmdata = $this->get('request')->get('fmdata', array());
            $this->get('db.wxmenus')->mulSave($fmdata,$appid); 
            return $this->success('操作成功');
        }
    }

    /**
    * 实现的delete方法
    * admina
    */
    /*
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $this->get('db.wxmenus')->delete($id);
        return $this->success('操作成功');
    }*/

    // 创建菜单create方法
    public function createAction()
    {
        $appid = $this->get('request')->get('appid','');

        if(empty($appid))
            return parent::error('无效的appid');

        $user = $this->getUser();
        $map = array();
        $map['uid'] = is_object($user)?$user->getId():0;
        $map['appid'] = $appid;

        $wxuser = $this->get('db.wxusers')->findOneBy($map);

        if(!is_object($wxuser))
            return parent::error('无效的appid信息');

        $this->get('oauth.weixin_menu')->init($appid);

        $flag = $this->get('oauth.weixin_menu')->create();

        $res = $flag ? '成功' : '失败';

        return $this->success("操作:$res");
    }
}