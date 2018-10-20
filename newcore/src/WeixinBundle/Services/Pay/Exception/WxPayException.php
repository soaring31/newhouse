<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年2月21日
*/
namespace WeixinBundle\Services\Pay\Exception;

/**
 * 
 * 微信支付API异常类
 * @author house
 *
 */
class WxPayException extends \Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}