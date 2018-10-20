<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年8月4日
 */
namespace CoreBundle\Services\Aliyun\Core\Regions;

class ProductDomain
{
	private $productName;
	private $domainName;
	
	function  __construct($product, $domain) {
		$this->productName = $product;
		$this->domainName = $domain;
	}
	
	public function getProductName() {
		return $this->productName;
	}
	public function setProductName($productName) {
		$this->productName = $productName;
	}
	public function getDomainName() {
		return $this->domainName;
	}
	public function setDomainName($domainName) {
		$this->domainName = $domainName;
	}

}