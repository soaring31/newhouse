<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月13日
*/
namespace HouseBundle\Controller;

/**
* 节点权限
* @author house
*/
class AuthoritygroupController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
	/**
    * 实现的show方法
    * admin
    */
    public function showAction()
    {
        $orderby = array('sort'=>'asc');
        //不限仅查询当前用户权限内的菜单，如需让角色配置者只能在自已所拥有的权限范围内分发权限，则设为 true
        $params = array('onlymine' => false, 'bundle' => 'house');
        $this->parameters['permissionsNode'] = $this->get('db.menus')->getMenuByLevel(1, $orderby, $params);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的save方法
    * admin
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id', 0);

            if($id>0)
                $this->get('db.auth_group')->update($id, $_POST);
            else
                $this->get('db.auth_group')->add($_POST);

            $this->get('core.rbac')->loginHandle($this->getUser());
            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * admin
    */
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id', 0);
        $this->get('db.auth_group')->delete($id);
        return $this->success('操作成功');
    }
}