<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月20日
*/
namespace HouseBundle\Controller;

/**
* 会员升级
* @author house
*/
class MuserupgradelistController extends Controller
{



    /**
    * 升级经纪人
    * house
    */
    public function upjjrAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 升级经纪公司
    * house
    */
    public function upjjgsAction()
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
                    $info = $this->get('db.users')->update($id, $data);
                }
            }else
                $info = $this->get('db.users')->add($data);

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
                $this->get('db.users')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}