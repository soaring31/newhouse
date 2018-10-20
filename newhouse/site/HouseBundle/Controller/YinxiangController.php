<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月17日
*/
namespace HouseBundle\Controller;

/**
* 前台印象
* @author house
*/
class YinxiangController extends Controller
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

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('house.houses_arc')->update($id, $_POST);
                }
            }else
                $info = $this->get('house.houses_arc')->add($_POST);

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
    * 首页
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}