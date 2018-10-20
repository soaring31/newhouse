<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月23日
*/
namespace HouseBundle\Controller;

/**
* 会员中心楼盘分销
* @author house
*/
class MusersellController extends Controller
{



    /**
    * 会员中心楼盘分销列表
    * house
    */
    public function muserselllistAction()
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
                    $info = $this->get('house.houses_arc')->update($id, $data);
                }
            }else
                $info = $this->get('house.houses_arc')->add($data);

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
                $this->get('house.houses_arc')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 实现的muserbrokerage方法
    * house
    */
    public function muserbrokerageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 佣金提取记录
    * house
    */
    public function muserextractAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}