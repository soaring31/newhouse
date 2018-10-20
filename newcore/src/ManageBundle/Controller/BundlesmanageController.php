<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年01月22日
*/
namespace ManageBundle\Controller;

/**
* Bundle管理
* @author admin
*/
class BundlesmanageController extends Controller
{
    /**
    * 实现的show方法
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $this->get('core.controller.command')->CreateBundle($this->get('request')->request->all());
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }
    
    /**
     * 更新资源包
     */
    public function upresourceAction()
    {
        $id = $this->get('request')->get('id', '');
        $this->parameters['status'] = false;
        
        if($id)
        {
            $this->get('core.common')->upResource($id);
            $this->parameters['status'] = true;
        }
        
        return $this->success('操作成功');
        //return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 更新Entity源
     */
    public function upentityAction()
    {
        $bundle = $this->get('request')->get('id', '');
        $this->parameters['status'] = false;
        
        if($bundle)
        {
            $this->get('core.controller.command')->createAllEntity($bundle);
            $this->parameters['status'] = true;
        }
        return $this->success('操作成功');
    }

}