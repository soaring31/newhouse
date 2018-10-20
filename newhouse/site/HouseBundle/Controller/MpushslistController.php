<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月25日
*/
namespace HouseBundle\Controller;

/**
* 推送列表
* @author house
*/
class MpushslistController extends Controller
{
    /**
    * 推送编辑
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

            // unset($data['name']);
            unset($data['csrf_token']);

            if(!empty($ids))
            {
                $ids = explode(',',$ids);
                foreach($ids as $id)
                {
                    $this->get('db.pushs')->update($id, $data, null);
                }
            } else {
                $this->get('db.pushs')->add($data);
            }
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
        $id = $this->get('request')->get('id', 0);
        $this->get('db.pushs')->delete($id);
        return $this->success('操作成功');
    }

    /**
    * 加载推送
    * house
    */
    public function loadpushsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}