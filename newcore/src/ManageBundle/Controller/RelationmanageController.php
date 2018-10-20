<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月09日
*/
namespace ManageBundle\Controller;

/**
* 类目关联管理
* @author house
*/
class RelationmanageController extends Controller
{



    /**
    * 实现的show方法
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

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db_relation')->update($id, $_POST);
                }
            }else
                $info = $this->get('db_relation')->add($_POST);

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
                $this->get('db_relation')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    public function relationbindAction()
    {
        $this->parameters['id'] = $this->get('request')->get('id', '');
        $data = $this->get('db_relation')->setRelation($this->parameters['id']);
        $this->parameters['master'] = $data['master'];
        $this->parameters['slave'] = $data['slave'];
        $this->parameters['already'] = json_encode(unserialize($data['already']));
        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function relationtestAction()
    {
        $id = $this->get('request')->get('id', '');
        $this->parameters['data'] = $this->get('db_relation')->getRelation($id);
        return $this->render($this->getBundleName(), $this->parameters);
    }

}