<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月08日
*/
namespace HouseBundle\Controller;
        
/**
* 通用评论
* @author house
*/
class IntercommentController extends Controller
{
    /**
    * 通用评论
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 评论列表
    * house
    */
    public function listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘点评
    * house
    */
    public function lplistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 问答列表
    * house
    */
    public function asklistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 图片评论列表
    * house
    */
    public function photolistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 网站提问列表
    * house
    */
    public function servicelistAction()
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
            
            unset($data['csrf_token']);

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('house.inter_comment')->update($id, $data);
                }
            }else
                $info = $this->get('house.inter_comment')->add($data);

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
                $this->get('house.inter_comment')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 店铺留言列表
    * house
    */
    public function shoplistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}