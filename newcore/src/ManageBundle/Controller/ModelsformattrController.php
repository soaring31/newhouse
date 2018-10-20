<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月18日
*/
namespace ManageBundle\Controller;

 
class ModelsformattrController extends Controller
{
    /**
     * 弹窗TWIG模版
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
            $id = (int)$this->get('request')->get('id');

            if($id>0)
                $this->get('db.model_form_attr')->update($id, $_POST);
            else
                $this->get('db.model_form_attr')->add($_POST);

            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }
    
    /**
     * 删除
     */
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id');
        $this->get('db.model_form_attr')->delete($id);
        return $this->success('操作成功');
    }
}