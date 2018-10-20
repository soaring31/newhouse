<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月27日
*/
namespace ManageBundle\Controller;

/**
* 
* @author admina
*/
class ModelsplanController extends Controller
{

    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.model_plan')->update($id, $_POST);
                }
            }else
                $info = $this->get('db.model_plan')->add($_POST);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.model_plan')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

   


    /**
    * 方案管理
    * admina
    */
    public function modelsplanfieldAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }



    /**
    * 实现的show方法
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}