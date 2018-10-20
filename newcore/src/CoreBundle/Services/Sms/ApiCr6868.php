<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年6月29日
 */
namespace CoreBundle\Services\Sms;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiCr6868 extends Sms{
	
	// base path
	private $baseurl = 'http://web.cr6868.com/asmx/smsservice.aspx';
	private $bparams = array();

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
			'name' => $this->appId,
			'pwd' => $this->appSecret,
		);
	}
	
	/**
	 * sms_cr6868；
	 * 发送内容（1-500 个汉字）UTF-8编码
	 * http://web.cr6868.com/asmx/smsservice.aspx?name=13537432147&pwd=xxx&content=test测试msg[和平鸽]&mobile=13537432147&type=pt
	 */
	function sendSMS($mobiles,$content='')
	{
	    $options = array();
		$options['url'] = $this->baseurl;
        $this->resourceOwner->setOption($options);
        $map = $this->bparams;
        $map['type'] = 'pt';
        $map['content'] = $content;
        $map['mobile'] = $mobiles;

        $data = $this->resourceOwner->getContent($map);

        $res = $this->errInfo($data); 
		return $res;
	}
	
	/**
	 * 余额查询 
	 * @return double 余额
	 */
	function getBalance()
	{
	    $options = array();

		$options['url'] = $this->baseurl; 
        $this->resourceOwner->setOption($options); 
        $map = $this->bparams;
        $map['type'] = 'balance';

        $data = $this->resourceOwner->getContent($map);

        $res = $this->errInfo($data); 
        return $res;
	}
	
	/**
	 * 错误代码-信息对照表
	 */
	public function errInfo($data,$re=0)
	{
		#dump($data);
		$err = array(
			'1'  => '含有敏感词汇', 
			'2'  => '余额不足',
			'3'  => '没有号码',
			'4'  => '包含sql语句',
			'10' => '账号不存在',
			'11' => '账号注销',
			'12' => '账号停用',
			'13' => 'IP鉴权失败',
			'14' => '格式错误',
			'-1' => '系统异常',
			'-2' => '其它错误！',
		);
		if($re) return $err;
		$a = explode(',',$data);
		if(count($a)<2){
			return array('-2','其它错误！');
		}elseif($a[0]==="0"){
			return array('1', $a[1]);
		}else{
			$no = $a[0]; $msg = $a[1];
			$msg || $msg = isset($err[$no]) ? $err[$no] : '(未知错误)';
			return array($no,"[$no]{$msg}");
		}
	}

}


/**
 * 以下问题, 请与短信供应商联络：
-1- (和平鸽)你们那个接口，延时厉害啊！(前天我在你们网站测试时，是很快的。)
 * 创瑞短信公司的客户经理 您现在不是免审,早上这一块发短信的人又多。延时肯定的会有的。
-2- 当发送含有一些关键字时；
 * 当时显示发送成功，其实没有发送成功；
 * 进cr6868.com网站后台可发现有“驳回”提示；
 * 但我们系统已经是按当时状态(成功)的来处理，这个是个问题。
 */

