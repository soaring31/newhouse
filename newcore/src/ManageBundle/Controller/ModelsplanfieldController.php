<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月28日
*/
namespace ManageBundle\Controller;

/**
* 
* @author admina
*/
class ModelsplanfieldController extends Controller
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

            $_POST['extra'] = isset($_POST['extra'])&&$_POST['extra']?preg_replace("/(，)|(;)|(；)/" ,',' ,$_POST['extra']):'';
            //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.model_fields')->update($id, $_POST);
                }
            }else
                $info = $this->get('db.model_fields')->add($_POST);

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
                $this->get('db.model_fields')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 字段模型的显示
    * admina
    */
    public function modelsplanfieldAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 显示添加字段
    * ilv
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}