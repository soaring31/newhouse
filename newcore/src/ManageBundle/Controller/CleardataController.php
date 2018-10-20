<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年05月17日
*/
namespace ManageBundle\Controller;

/**
* 清除数据
* @author house
*/
class CleardataController extends Controller
{
    /**
    * 清除数据
    * house
    */
    public function clearAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 清除数据表单
    * house
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

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
                    $info = $this->get('db.cleardata')->update($id, $data);
                }
            }else
                $info = $this->get('db.cleardata')->add($data);

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
                $this->get('db.cleardata')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 实现的clearone方法
    * house
    */
    public function clearoneAction()
    {
        $map = array();
        $map['id'] = (int)$this->get('request')->get('id',0);
        $info = $this->get('db.cleardata')->findBy($map);
        $this->get('db.cleardata')->clear($info);
        return parent::success('执行成功');
    }

    /**
    * 执行所有
    * house
    */
    public function clearallAction()
    {
        $this->get('db.cleardata')->clear();
        return parent::success('执行成功');
    }
}