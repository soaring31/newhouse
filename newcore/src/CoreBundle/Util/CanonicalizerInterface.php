<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-05-29
*/
namespace CoreBundle\Util;

interface CanonicalizerInterface {
	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function canonicalize($string);
}

?>