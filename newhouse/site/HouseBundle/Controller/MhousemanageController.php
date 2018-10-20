<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月24日
*/
namespace HouseBundle\Controller;

/**
* 楼盘管理
* @author house
*/
class MhousemanageController extends Controller
{
    /**
    * 楼盘列表
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

            if(!empty($ids))
            {
                $ids = explode(',',$ids);
                foreach($ids as $id)
                {
                    $this->get('house.houses')->update($id, $data);
                }
                return $this->success('操作成功');
            }else{
                $info = $this->get('house.houses')->add($data);
                $this->get('house.houses')->relationByHouse($info);
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
        $this->get('house.houses')->delete($id);
        return $this->success('操作成功');
    }

    /**
    * 电子沙盘图片编辑
    * house
    */
    public function mshapaneditAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}