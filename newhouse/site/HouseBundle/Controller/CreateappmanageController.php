<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年10月12日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class CreateappmanageController extends Controller
{
	/**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            
            $data = $this->get('request')->request->all();
            
            unset($data['csrf_token']);

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.createapp')->update($id, $data);
                }
            }else
                $info = $this->get('db.createapp')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.createapp')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 生成单个app模版
    * house
    */
    public function createoneAction()
    {
        $map = array();
        $map['id'] = (int)$this->get('request')->get('id',0);
        $info = $this->get('db.createapp')->findBy($map);
        $this->get('db.createapp')->generate($info);
        return parent::success('执行成功');
    }

    /**
    * 生成全部app模版
    * house
    */
    public function createallAction()
    {
        $this->get('db.createapp')->generate();
        return parent::success('执行成功');
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