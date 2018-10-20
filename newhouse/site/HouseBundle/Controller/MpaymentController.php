<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月12日
*/
namespace HouseBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
* 充值记录
* @author house
*/
class MpaymentController extends Controller
{
    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {      
        $data = $this->get('request')->query->all();
        
        unset($data['csrf_token']);
        
        // consume=10为购买优惠券
        if (isset($data['consume']) && $data['consume'] == 10) {
            //判断验证码
            if(!$this->get('core.sms')->checkSmscode($data['tel'], $data['codeNew']))
                throw new \LogicException('验证码错误');
            //自动注册并登陆
            $this->get('db.users')->autoLogin($data['tel']);
        
            // 获取优惠券信息
            $coupons = $this->get('house.coupons')->findOneBy(array('id'=>$data['aid']), array(), false);
            if (!is_object($coupons))
                throw new \LogicException("该优惠券不存在！");
        
            // 获取优惠券价格
            $data['total_fee'] = $coupons->getPrice();
            // $data['total_fee'] = 0;
            // $add = array();
            // $add['type'] = 1;
            // $add['tel'] = $data['tel'];
            // $add['aid'] = $data['aid'];
            // $user = $this->get('core.common')->getUser();
            // $add['uid'] = is_object($user)?$user->getId():$data['uid'];
            // $which = $this->get('request')->get('wxpay','');
            // if ($which != '') {
            //     $which = 'wxpay';
            // } elseif ($which = $this->get('request')->get('alipay','')) {
            //     $which = 'alipay';
            // }
            // $this->get('db.orderlist')->_addCouponsorder($add, $which);
        }

        $this->parameters['info'] = $this->get('db.orderlist')->add($data);

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
                $this->get('house.orderlist')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}