<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月12日
*/
namespace HouseBundle\Controller;

/**
* 经纪人列表
* @author house
*/
class MuseragentlistController extends Controller
{



    /**
    * 编辑
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
         //特殊字段
            $data['cid'] = 0;
            
            unset($data['csrf_token']);
            if($ids)
            {
                $ids = explode(',', $ids);
                
                //防止修改其他人的数据
                $map = array();
                $map['id']['in'] = $ids;
                $map['cid'] = $this->get('core.common')->getUser()->getId();
                $result = $this->get('db.userinfo')->findBy($map);
                
                foreach ($result['data'] as $item)
                {
                    $this->get('db.userinfo')->setNoCheck(true);
                    $info = $this->get('db.userinfo')->update($item->getId(), $data);
                }
            }else
                $info = $this->get('db.userinfo')->add($data);

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
                $this->get('house.nexus_arc')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}