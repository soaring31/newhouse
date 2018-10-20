<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月30日
*/
namespace HouseBundle\Controller;

/**
* 经纪人、经纪公司
* @author house
*/
class MemberController extends Controller
{



    /**
    * 经纪人列表
    * house
    */
    public function j_listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪人详情
    * house
    */
    public function j_detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪人详情出租
    * house
    */
    public function j_d_rentAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪人详情介绍
    * house
    */
    public function j_d_dsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 店铺留言
    * house
    */
    public function j_d_lyAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司旗下的经纪人
    * house
    */
    public function d_jjrAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司动态
    * house
    */
    public function d_newsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
    * 经纪公司列表
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司二手房
    * house
    */
    public function detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司出租
    * house
    */
    public function d_rentAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司简介
    * house
    */
    public function d_dsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 公司动态详情
    * house
    */
    public function d_news_detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 实现的k_list方法
    * house
    */
    public function k_listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的k_detail方法
    * house
    */
    public function k_detailAction()
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
                    $info = $this->get('house.usermsg')->update($id, $data);
                }
            }else
                $info = $this->get('house.usermsg')->add($data);

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
                $this->get('house.usermsg')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 介绍app
    * house
    */
    public function jsblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 留言app
    * house
    */
    public function lyblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 资讯详情
    * house
    */
    public function newsblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 开发商详情app
    * house
    */
    public function kdetailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}