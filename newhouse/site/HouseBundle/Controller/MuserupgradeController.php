<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月10日
*/
namespace HouseBundle\Controller;

/**
* 用户升级申请
* @author house
*/
class MuserupgradeController extends Controller
{



    /**
    * 用户升级申请
    * house
    */
    public function muserupgradelistAction()
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
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.workflow')->update($id, $data);
                }
            }else
                $info = $this->get('db.workflow')->add($data);

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
                $this->get('db.workflow')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 升级经纪人
    * house
    */
    public function agentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}