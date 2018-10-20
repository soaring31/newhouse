<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月18日
*/
namespace HouseBundle\Controller;
        
/**
* 房产参数
* @author house
*/
class MmemberparamsController extends Controller
{
        


    /**
    * 经纪人升级
    * house
    */
    public function membervipAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 房源发布
    * house
    */
    public function msaleaddAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 可选模块
    * house
    */
    public function mabledAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 其它参数
    * house
    */
    public function motherAction()
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
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.mconfig')->update($id, $data);
                }
            }else
                $this->get('db.mconfig')->add($data);
        
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
                $this->get('db.mconfig')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    

    /**
    * 会员房源发布规则
    * house
    */
    public function msaleaddmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 置顶配置
    * house
    */
    public function mtopmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 预约刷新方案
    * house
    */
    public function mrefreshmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘分销
    * house
    */
    public function msellAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 经纪人升级配置
    * house
    */
    public function mmembersvipAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}