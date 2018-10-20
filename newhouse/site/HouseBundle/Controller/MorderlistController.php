<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年04月19日
*/
namespace HouseBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
* 订单管理
* @author house
*/
class MorderlistController extends Controller
{
    /**
    * 订单管理列表
    * house
    */
    public function morderlistmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 重新请求
    * house
    */
    public function mrequestAction()
    {
        $id = (int)$this->get('request')->get('id',0);
        
        $info = $this->get('db.orderlist')->findOneBy(array('id'=>$id), array(), false);
        if(!is_object($info))
            return parent::error('数据不存在或已被删除');

        if($info->getChecked())
            return parent::success('支付成功');

        // 重新支付
        $this->parameters['info'] = $this->get('db.orderList')->againPay($id);

        //判断是否是跳转
        if($this->parameters['info'] instanceof RedirectResponse)
            return $this->parameters['info'];
        
        //判断是否余额支付成功
        if(is_object($this->parameters['info']))
        {
            //组装模版路径
            $template = $this->getBundleName(true).':'.ucfirst($this->getControllerName()).':success.html.twig';
        
            return $this->render1($template, $this->parameters);
        }
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}