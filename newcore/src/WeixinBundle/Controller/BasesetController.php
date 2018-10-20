<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace WeixinBundle\Controller;
        
/**
* 基础设置
* @author admina
*/
class BasesetController extends Controller
{
    /**
    * 支付配置
    * admina
    */
    public function basepayAction()
    {
        $user = $this->getUser();
        
        $map = array();
        $map['uid'] = $user->getId();
        $map['appid'] = $this->get('request')->getSession()->get('appid');
        $map['type'] = 'weixin';
        $map['appsecret'] = $this->get('request')->getSession()->get('appsecret');

        
        $this->parameters['info'] = $this->get('db.payment')->findOneBy($map);

        if($this->get('request')->getMethod() == "POST")
        {
            if(empty($this->parameters['info']))
                $this->get('db.payment')->add(array_merge($_POST, $map));
            else
                $this->get('db.payment')->update($this->parameters['info']->getId(),array_merge($_POST, $map));

            return $this->success('操作成功');
        }
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 菜单配置
    * admina
    */
    public function basemenuAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}