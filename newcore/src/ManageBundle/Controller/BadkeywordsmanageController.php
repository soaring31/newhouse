<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月14日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class BadkeywordsmanageController extends Controller
{
        
   /**
    * 不良词添加
    * admina
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 保存、修改
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');

            if($id>0)
                $this->get('db.badkeywords')->update($id, $this->get('request')->request->all());
            else
                $this->get('db.badkeywords')->add($this->get('request')->request->all());
        
            //清除缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
    
    /**
     * 模版删除
     */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id');
        #if($this->get('request')->getMethod() == "POST")
        {
            $this->get('db.badkeywords')->delete($id);
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }

}