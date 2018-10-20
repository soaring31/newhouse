<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月12日
*/
namespace HouseBundle\Controller;
        
/**
* 公用列表页
* @author house
*/
class ClistController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    /**
    * 图库列表
    * house
    */
    public function photolistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 历史价格表格
    * house
    */
    public function lsjgtableAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 户型图内容公用模板
    * house
    */
    public function planintroAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 资讯列表
    * house
    */
    public function newslistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 视频列表
    * house
    */
    public function videolistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 商业中心列表
    * house
    */
    public function business_listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 二手房列表
    * house
    */
    public function salelistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租列表
    * house
    */
    public function rentlistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪人列表
    * house
    */
    public function jjr_listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 新房列表
    * house
    */
    public function houseslistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 开发商列表
    * house
    */
    public function kfs_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 服务列表
    * house
    */
    public function servicelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 特价房列表
    * house
    */
    public function special_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 问答列表页
    * house
    */
    public function asklistAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 团购列表
    * house
    */
    public function groupbuy_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 置业指南列表
    * house
    */
    public function guidelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 户型列表
    * house
    */
    public function door_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司列表
    * house
    */
    public function jjgs_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 学区找房
    * house
    */
    public function school_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 需求列表
    * house
    */
    public function demand_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 小区列表
    * house
    */
    public function xq_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 专题列表
    * house
    */
    public function topiclistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘相册列表
    * house
    */
    public function xiangce_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 经纪公司所属经纪人列表
    * house
    */
    public function jjr_jjgs_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 楼盘分销列表
    * house
    */
    public function fenxiao_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 收藏列表
    * house
    */
    public function handlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 收藏二手房
    * house
    */
    public function salehandlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 出租收藏列表
    * house
    */
    public function renthandlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 印象列表
    * house
    */
    public function yx_listAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 文字直播列表
    * house
    */
    public function zblistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 客户端下载提示
    * house
    */
    public function downloadAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 客户端下载提示
    * house
    */
    public function photo_conAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 定位列表
    * house
    */
    public function locallistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 专题
    * house
    */
    public function topiclist2Action()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的aroundrelate方法
    * house
    */
    public function aroundrelateAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的photocon方法
    * house
    */
    public function photoconAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 我的分销
    * house
    */
    public function selllistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 佣金
    * house
    */
    public function brokeragelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 佣金提取
    * house
    */
    public function extractlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 下级分销列表
    * house
    */
    public function subordinatelistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 邀请下线明细列表
    * house
    */
    public function yqmxlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}