<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年8月4日
 */
namespace CoreBundle\Services\Aliyun\Dysmsapi\Request\V20170525;

use CoreBundle\Services\Aliyun\Core\RpcAcsRequest;

class QueryInterSmsIsoInfoRequest extends RpcAcsRequest
{
	function  __construct()
	{
		parent::__construct("Dysmsapi", "2017-05-25", "QueryInterSmsIsoInfo");
		$this->setMethod("POST");
	}
	
	private  $ownerId;

	private  $countryName;

	private  $resourceOwnerId;
	
	private  $resourceOwnerAccount;

	public function getResourceOwnerAccount()
	{
		return $this->resourceOwnerAccount;
	}

	public function setResourceOwnerAccount($resourceOwnerAccount)
	{
		$this->resourceOwnerAccount = $resourceOwnerAccount;
		$this->queryParameters["ResourceOwnerAccount"]=$resourceOwnerAccount;
	}

	public function getCountryName()
	{
		return $this->countryName;
	}

	public function setCountryName($countryName)
	{
		$this->countryName = $countryName;
		$this->queryParameters["CountryName"]=$countryName;
	}

	public function getResourceOwnerId()
	{
		return $this->resourceOwnerId;
	}

	public function setResourceOwnerId($resourceOwnerId)
	{
		$this->resourceOwnerId = $resourceOwnerId;
		$this->queryParameters["ResourceOwnerId"]=$resourceOwnerId;
	}

	public function getOwnerId()
	{
		return $this->ownerId;
	}

	public function setOwnerId($ownerId)
	{
		$this->ownerId = $ownerId;
		$this->queryParameters["OwnerId"]=$ownerId;
	}
	
}