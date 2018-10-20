<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年8月4日
 */
namespace CoreBundle\Services\Aliyun\Core\Regions;

class EndpointProvider
{
	private static $endpoints;
	
	public static function findProductDomain($regionId, $product)
	{
		if(null == $regionId || null == $product || null == self::$endpoints)
		{
			return null;
		}
		
		foreach (self::$endpoints as $endpoint)
		{
			if(in_array($regionId, $endpoint->getRegionIds()))
			{
			 	return self::findProductDomainByProduct($endpoint->getProductDomains(), $product);
			}	
		}
		return null;
	}
	
	private static function findProductDomainByProduct($productDomains, $product)
	{
		if(null == $productDomains)
		{
			return null;
		}
		foreach ($productDomains as $productDomain)
		{
			if($product == $productDomain->getProductName())
			{
				return $productDomain->getDomainName();
			}
		}
		return null;
	}
	
	
	public static function getEndpoints()
	{
		return self::$endpoints;
	}
	
	public static function setEndpoints($endpoints)
	{
		self::$endpoints = $endpoints;
	}
	
}