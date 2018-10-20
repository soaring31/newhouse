<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月24日
*/
namespace HouseBundle\Controller;
        
/**
* 楼盘
* @author house
*/
class MhouseController extends Controller
{
        


    /**
    * 楼盘管理
    * house
    */
    public function mhousemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    

    /**
    * 实现的minterhousesmanage方法
    * house
    */
    public function minterhousesmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘点评列表
    * house
    */
    public function minterhousescmtAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 楼盘文档管理
    * house
    */
    public function mhousearcmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 楼盘价格管理
    * house
    */
    public function mhousepricemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 小区管理
    * house
    */
    public function mcourtmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    
    /**
    * 区块团购管理
    * house
    */
    public function mgroupbuymanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块相册管理
    * house
    */
    public function malbummanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块户型管理
    * house
    */
    public function mdoormanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块意向管理
    * house
    */
    public function minterintentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块红包管理
    * house
    */
    public function mredmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块特价房管理
    * house
    */
    public function mbargainmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块印象管理
    * house
    */
    public function minterimpressmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块楼栋管理
    * house
    */
    public function mbuildmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块点评管理
    * house
    */
    public function mcommentmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘文档列表
    * house
    */
    public function mhousesarcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 区块楼盘分销
    * house
    */
    public function msellmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘分销推荐
    * house
    */
    public function mrecommendAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘分销佣金提取
    * house
    */
    public function mwagesAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的news1方法
    * house
    */
    public function news1Action()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 电子沙盘
    * house
    */
    public function mshapanAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    
    /**
    * 电子沙盘图片编辑
    * house
    */
    public function mshapaneditAction()
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
                    $info = $this->get('house.houses_arc')->update($id, $data);
                }
            }else
                $info = $this->get('house.houses_arc')->add($data);

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
    * 红包报名
    * house
    */
    public function mredenrollAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的madvisermanage方法
    * house
    */
    public function madvisermanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘动态管理
    * house
    */
    public function mtrendsmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘附属信息管理
    * house
    */
    public function mhouseinfomanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘动态展示
    * house
    */
    public function mloupandongtaiAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘动态展示
    * house
    */
    public function mloupandongtaimanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 楼盘交房楼盘管理
     * house
     */
    public function mhousemakemanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}