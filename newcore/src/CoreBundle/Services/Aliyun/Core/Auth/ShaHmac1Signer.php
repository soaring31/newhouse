<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年8月4日
 */
namespace CoreBundle\Services\Aliyun\Core\Auth;

class ShaHmac1Signer implements ISigner
{
	public function signString($source, $accessSecret)
	{
		return	base64_encode(hash_hmac('sha1', $source, $accessSecret, true));
	}
	
	public function  getSignatureMethod() {
		return "HMAC-SHA1";
	}

	public function getSignatureVersion() {
		return "1.0";
	}

}