<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月02日
*/
namespace HouseBundle\Controller;

/**
* 升级经纪人
* @author house
*/
class MuagentController extends Controller
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
                    $info = $this->get('house.orderlist')->update($id, $data);
                }
            }else
                $info = $this->get('house.orderlist')->add($data);

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
                $this->get('house.orderlist')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 升级经纪人表单
    * house
    */
    public function muagentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}