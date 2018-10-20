<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月03日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 退款列表
* 
*/
class Refundlist extends AbstractServiceManager
{
    protected $table = 'Refundlist';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 对支付宝退款订单进行处理
     * @param  array  $data  订单数据数组
     * @return 检查结果
     */
    public function refundHandleAli($data)
    {
    	if($data['success_num'] !== 1 || !isset($data['result_details']))
    		return false;

    	$data['paytype'] = 'alipay';

		if(strpos('$', $data['result_details']) !== false){
			$order = explode('$', $data['result_details']);
    		$refund = explode('^', $order[0]);
    		$counter = explode('^', $order[1]);

    		$data['refund_email'] = $counter[0];
    		$data['refund_user'] = $counter[1];
    		$data['counter_fee'] = $counter[2];
    		$data['counter_status'] = $counter[3];
		}else{
			$refund = explode('^', $data['result_details']);
		}
		
		$data['trade_no'] = $refund[0];
		$data['refund_fee'] = $refund[1];
		$data['status'] = $refund[2];
		
		$this->get('db.orderlist')->upOrderByTrade($data['trade_no'], $data['status']); //!!未测试！//??
		unset($data['result_details']);

		$result = parent::findOneBy(array('batch_no' => $data['batch_no']));
    	if(!empty($result))
    	{
            parent::update($result->getId(), $data, $result);
    	}else{
        	parent::add($data);
    	}
    }
}