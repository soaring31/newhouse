<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月18日
*/
namespace ManageBundle\Controller;
        
/**
* 会员架构
* @author admin
*/
class UserframeController extends Controller
{
    /**
    * 会员模型
    * admin
    */
    public function userframemodelsAction()
    {
        $map = array();
        $this->parameters['info'] = $this->get('db.user_models')->findBy($map);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员认证类型
    * admin
    */
    public function userframeauthAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}