<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月20日
*/
namespace HouseBundle\Controller;

/**
* 管理
* @author house
*/
class MaroundmanageController extends Controller
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

            if(isset($data['map']) && strpos($data['map'], ','))
                $data = $this->get('house.houses')->setGeohash($data);

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('house.around')->update($id, $data);
                    $this->get('house.around')->relationByAround($info);
                }
            }else{
                $info = $this->get('house.around')->add($data);
                $this->get('house.around')->relationByAround($info);
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
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('house.around')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}