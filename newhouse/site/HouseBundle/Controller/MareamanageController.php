<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月09日
*/
namespace HouseBundle\Controller;

/**
* 管理区域
* @author house
*/
class MareamanageController extends Controller
{
    /**
    * 编辑
    * house
    */
    public function showAction()
    {
        $pid = $this->get('request')->get('pid', '');
        if($pid)
            $parent = $this->get('db.area')->findOneBy(array('id' => $pid));

        if(!empty($parent))
            $this->get('request')->query->set('level', $parent->getLevel() + 1);

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

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.area')->update($id, $data);
                }
            }else
                $this->get('db.area')->add($data);

            return $this->success('操作成功');
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
                $this->get('db.area')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 区域子级
    * house
    */
    public function childrenAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }
}