<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年6月29日
 */
namespace CoreBundle\Services\Sms;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiWinic extends Sms{
	
	// base path
	private $baseurl = 'http://service.winic.org';
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
			'uid' => $this->appId,
			'pwd' => $this->appSecret,
		);
	}
	
	/**
	 * sms_winic；
	 */
	function sendSMS($mobiles,$content='')
	{
	    $options = array();
		$options['url'] = $this->baseurl."/sys_port/gateway/"; 
        $this->resourceOwner->setOption($options); 
        $map = $this->bparams; 
        $map['id'] = $map['uid']; unset($map['uid']);
        $map['content'] = $content; 
        $map['to'] = $mobiles; 
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
		$options['url'] = $this->baseurl.":8009/webservice/public/remoney.asp"; 
        $this->resourceOwner->setOption($options); 
        $map = $this->bparams; 
        $data = $this->resourceOwner->getContent($map); 
        $res = $this->errInfo($data,1); 
        #if(substr($res[1],0,1)=='.') // .80元
        $res[1] = 10 * intval($res[1]); // 转化成条
        return $res;
	}
	
	/**
	 * 错误代码-信息对照表
	 */
	public function errInfo($data,$re=0)
	{
		#dump($data); // -04/Send:1/Consumption:0/Tmoney:0/sid:
		$err = array(
			'-01' => '当前账号余额不足！',
			'-02' => '当前用户ID错误！',
			'-03' => '当前密码错误！',
			'-04' => '参数不够或参数内容的类型错误！',
			'-05' => '手机号码格式不对！',
			'-06' => '短信内容编码不对！',
			'-07' => '短信内容含有敏感字符！',
			'-09' => '系统维护中... ',
			'-10' => '手机号码数量超长！', //短信内容超长！（70个字符）目前已取消
			'-11' => '短信内容超长！',
			'-12' => '其它错误！',
		);
		if($re) return $err;
		$a = explode('/',$data);
		if(count($a)<2){
			return array('-2','其它错误！');
		}elseif($a[0]==="000"){
			return array('1', $a[1]);
		}else{
			$no = $a[0]; $msg = $a[1];
			$msg || $msg = isset($err[$no]) ? $err[$no] : '(未知错误)';
			return array($no,"[$no]{$msg}");
		}
	}

}

