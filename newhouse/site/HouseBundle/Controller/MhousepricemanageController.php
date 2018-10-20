<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月23日
*/
namespace HouseBundle\Controller;

/**
* 楼盘价格编辑
* @author house
*/
class MhousepricemanageController extends Controller
{
    /**
    * 实现的show方法
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
        $input = $this->get('request')->request->all();
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('house.houses_price')->update($id, $input);
                }

            }else
                $this->get('house.houses_price')->add($input);

            //$this->get('house.houses')->update($input['aid'], $input);
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
                $this->get('house.houses_price')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}