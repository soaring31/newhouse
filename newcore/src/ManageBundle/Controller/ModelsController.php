<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月15日
*/
namespace ManageBundle\Controller;

/**
 * 模型设置
 */
class ModelsController extends Controller
{
    /**
     * 模型管理
     */
    public function modelsmanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 表单管理
     */
    public function modelsformAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 表单属性
     */
    public function modelsformattrAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 字段管理
     */
    public function modelsfieldAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 回收站
    * admina
    */
    public function trashAction()
    {
        $map = array();
        $map['id'] = $this->get("request")->get("_id",0);
        
        $info = $this->get("db.models")->findOneBy($map);
        $this->parameters['result'] = array();
        $this->parameters['serviceName'] = '';
        if(is_object($info))
        {
            $map = $orderBy = array();
            // 按降序排序
            $orderBy['update_time'] = 'desc';
            $map['pageIndex'] = $this->get("request")->get("pageIndex",1);
            $this->parameters['result'] = $this->get($info->getServiceName())->getTrash($map,$orderBy);
            $this->parameters['serviceName'] = $info->getServiceName();
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 模型方案管理
    * admina
    */
    public function modelsplanAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 显示添加字段
    * admina
    */
    public function modelsplanfieldAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}