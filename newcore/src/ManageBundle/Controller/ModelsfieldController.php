<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年01月20日
*/
namespace ManageBundle\Controller;

/**
* 模型字段管理
* @author admin
*/
class ModelsfieldController extends Controller
{
    /**
    * 实现的show方法
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
    * 实现的save方法
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');
            //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
            $_POST['extra'] = isset($_POST['extra'])&&$_POST['extra']?preg_replace("/(，)|(;)|(；)/" ,',' ,$_POST['extra']):'';
        
            if($id>0)
                $this->get('db.model_attribute')->update($id, $_POST);
            else
                $this->get('db.model_attribute')->add($_POST);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
    
    /**
    * 实现的delete方法
    */
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id');
        $this->get('db.model_attribute')->delete($id);
        return $this->success('操作成功');
    }

    /**
     * 实现的create方法
     */
    public function createAction()
    {
        $id = $this->get('request')->get('_id');
        $this->get('db.model_attribute')->createDbTable($id);
        return $this->render($this->getBundleName(), $this->parameters);
    }
}