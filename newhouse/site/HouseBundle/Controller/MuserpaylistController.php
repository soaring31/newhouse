<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月28日
*/
namespace HouseBundle\Controller;

/**
* 支付列表
* @author house
*/
class MuserpaylistController extends Controller
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
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('house.userpay')->update($id, $data);
                }
            }else
                $info = $this->get('house.userpay')->add($data);

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
                $this->get('house.userpay')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

   
    /**
    * 编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 在线支付
    * house
    */
    public function payonlineAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 其他支付
    * house
    */
    public function paygiroAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}