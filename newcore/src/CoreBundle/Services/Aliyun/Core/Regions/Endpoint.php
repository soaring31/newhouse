<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年8月4日
 */
namespace CoreBundle\Services\Aliyun\Core\Regions;

class Endpoint
{
	private $name;
	private $regionIds; 
	private $productDomains;
	
	function  __construct($name, $regionIds, $productDomains)
	{
		$this->name = $name;
		$this->regionIds = $regionIds;
		$this->productDomains = $productDomains;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getRegionIds()
	{
		return $this->regionIds;
	}
	
	public function setRegionIds($regionIds)
	{
		$this->regionIds = $regionIds;
	}
	
	public function getProductDomains()
	{
		return $this->productDomains;
	}
	
	public function setProductDomains($productDomains)
	{
		$this->productDomains = $productDomains;
	}
}