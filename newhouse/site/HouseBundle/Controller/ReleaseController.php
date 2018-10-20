<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月21日
*/
namespace HouseBundle\Controller;

/**
* 发布信息
* @author house
*/
class ReleaseController extends Controller
{



    /**
    * 添加
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
        $models = $this->get('request')->get('models', '');

        if(empty($models))
            return $this->error('操作失败');

        $service = 'house.'.$models;
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get($service)->update($id, $data);
                }
            }else{
                $result = $this->get($service)->add($data);
                $user = $this->get('core.common')->getUser();
                if ($user)
                {
                    $result->setUid($user->getId());
                    $this->get($service)->update($result->getId(), array(), $result, false);
                }
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
                $this->get('house.sale')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 发布二手房
    * house
    */
    public function saleAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    

    /**
    * 发布二手房
    * house
    */
    public function usaleAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发布出租
    * house
    */
    public function urentAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发布求购
    * house
    */
    public function usdemandAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发布求租
    * house
    */
    public function urdemandAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 二手写字楼
    * house
    */
    public function uofficeAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租商铺
    * house
    */
    public function ushopsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租写字楼
    * house
    */
    public function urofficeAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发布商铺
    * house
    */
    public function urshopsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 委托求租
    * house
    */
    public function wthouseAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 委托出售
    * house
    */
    public function wtbuyAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 头部
    * house
    */
    public function headerblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪人
    * house
    */
    public function ujjdAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}