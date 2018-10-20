<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月21日
*/
namespace HouseBundle\Controller;

/**
* 积分兑换
* @author house
*/
class MusercashController extends Controller
{



    /**
    * 积分兑换
    * house
    */
    public function cashingAction()
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
            $data = $this->get('request')->request->all();
            unset($data['csrf_token']);

            $this->get('db.spend')->exchange($data);

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
                $this->get('db.spend')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}