<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年04月14日
*/
namespace HouseBundle\Controller;

/**
* 委托经纪人
* @author house
*/
class MentrustarcController extends Controller
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
            $uids = $this->get('request')->get('uid','');
            $data = $this->get('request')->request->all();
            
            unset($data['csrf_token']);

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('house.entrust_arc')->update($id, $data);
                }
            }elseif($uids)
            {
                $uids = explode(',', $uids);
                foreach($uids as $uid)
                {
                    //判断是否为重复添加
                    $map = array();
                    $map['aid'] = isset($data['aid'])?$data['aid']:0;
                    $map['uid'] = $uid;
                    
                    $re = $this->get('house.entrust_arc')->findOneBy($map, array(), false);
                    if (!$re)
                    {
                        $data['uid'] = $uid;
                        $info = $this->get('house.entrust_arc')->add($data);
                    }
                }
            }else
                $info = $this->get('house.entrust_arc')->add($data);

            return $this->success('操作成功', '', false, array('id' => isset($info)?$info->getId():0));
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
                $this->get('house.entrust_arc')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 委托经纪人加载
    * house
    */
    public function mentrustarcloadingAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 加载经纪人编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}