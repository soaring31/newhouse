<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月02日
*/
namespace ManageBundle\Controller;
        
/**
* 模版管理
* @author admin
*/
class ControllerstemplateController extends Controller
{
    /**
    * 模版新增弹窗TWIG
    * admin
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 新增、修改
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = $this->get('request')->get('id');
            $clonetpl = $this->get('request')->get('clonetpl','');
            if($id>0)
                $this->get('db.views')->update($id, $_POST);
            else
                $this->get('core.controller.command')->createViews($_POST, $clonetpl);
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }

    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id', 0);
    
        $this->get('db.views')->delete($id);
    
        return $this->success('操作成功');
    }

    /**
     * 数据标识
     */
    public function identAction()
    {
        $id = $this->get('request')->get('id', 0);
        $map = array();
        $map['id'] = $id;
        $views = $this->get('db.views')->findOneBy($map);
        $this->parameters['info'] = is_object($views)?$this->get('core_ident')->getYmlVal($views->getName()):array();
        return $this->render($this->getBundleName(), $this->parameters);
    }
}