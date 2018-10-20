<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月16日
*/
namespace HouseBundle\Controller;

/**
* 域名编辑管理
* @author house
*/
class MdomainsmanageController extends Controller
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
                    $info = $this->get('house.domains')->update($id, $data);
                }
            }else
                $info = $this->get('house.domains')->add($data);

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
                $this->get('house.domains')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 域名编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}