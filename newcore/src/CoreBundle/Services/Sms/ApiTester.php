<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年6月29日
 */
namespace CoreBundle\Services\Sms;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 仅测试使用，用于测试系统其它流程；
 * 具体操作不会发短信，仅写一个记录到数据库，后台查看
 */
class ApiTester extends Sms{
	
	// base path
	private $baseurl = 'http://www.08cms.com';
	private $bparams = array();
	private $charge = 0;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->_init(); 
    }

	// 各接口更多参数配置
	private function _init()
	{
		// 参数配置
		$this->bparams = array(
			'uid' => $this->appId,
			'pwd' => $this->appSecret,
		);
		$this->charge = $this->get('core.common')->getParameter('sms_tester_balance');
	}
	
	/**
	 * 余额查询
	 * @return double 余额
	 */
	public function getBalance()
	{
		$rnd = rand(1,1000);
		if($rnd<998){ // 模拟,99.8%情况下成功
			return array(1,$this->charge);
		}else{
			return array(-1,'失败!');
		}	
	}

	/**
	 * 具体操作不会发短信，仅写一个记录到数据库，后台查看
	 */
	public function sendSMS($mobiles,$content='')
	{
		$rnd = rand(1,1000);
		if($rnd<900){ // 模拟,90%情况下成功
			$res = array(1,"OK!");
		}else{
			$res = array(-1,'失败!');
		}
		$this->chargeSave(1); //模拟扣钱
		return $res;
	}

	/**
	 * 错误代码-信息对照表
	 */
	public function errInfo($data,$re=0)
	{
		$err = array(
			'1' => '成功',
			'0' => '失败',
		);
		if($re) return $err;
		// do sth.
	}

	/**
	 * save: 充值($op='add'), 扣费($op='sub')
	 */
	public function chargeSave($count,$op='sub')
	{
		$cnt = $this->charge;
		if($op=='sub'){
			$cnt -= $count; 
			if((int)$cnt<0) $cnt = 0; 
		}else{
			$cnt += $count; 
		}
//         $cfgPath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_sms.yml";
//         $cnfInfo = $this->get('core.ymlParser')->ymlRead($cfgPath); 
//         $cnfInfo['parameters']['sms_tester_balance'] = $this->charge = $cnt;
//     	$this->get('core.ymlParser')->ymlWrite($cnfInfo, $cfgPath);
//     	//清空缓存
//     	$this->get('core.common')->cleanCache();
    	return array(1,$cnt);
	}
	

}

// 附加说明
// none

