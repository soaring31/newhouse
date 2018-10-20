<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年05月03日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class MhousemakemanageController extends Controller
{

    /**
     * 实现的show方法
     */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
     * 楼盘交房信息保存
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
                    $this->get('house.house_make')->update($id, $data);
                }
            }else
                $this->get('house.house_make')->add($data);

            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }

    /**
     * 楼盘交房信息删除
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
                $this->get('house.house_make')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

}