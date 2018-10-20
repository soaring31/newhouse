<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年2月21日
*/
namespace WeixinBundle\Services\Pay;

use CoreBundle\Services\ServiceBase;
use OAuthBundle\WeiXin\Pay\Request\WxPayBizPayUrl;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * 刷卡支付实现类
 * @author house
 *
 */
class NativePay extends ServiceBase
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

	/**
	 * 
	 * 生成扫描支付URL,模式一
	 * @param BizPayUrlInput $bizUrlInfo
	 */
	public function GetPrePayUrl($productId)
	{
		$biz = new WxPayBizPayUrl();
		$biz->setProductId($productId);
		$values = WxPay::bizpayurl($biz);
		return "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
	}

	/**
	 * 
	 * 参数数组转换为url参数
	 * @param array $urlObj
	 */
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			$buff .= $k . "=" . $v . "&";
		}
		
		return trim($buff, "&");
	}

	/**
	 * 
	 * 生成直接支付url，支付url有效期为2小时,模式二
	 * @param UnifiedOrderInput $input
	 */
	public function GetPayUrl($input)
	{
		if($input->GetTrade_type() == "NATIVE")
			return WxPay::unifiedOrder($input);
	}
}