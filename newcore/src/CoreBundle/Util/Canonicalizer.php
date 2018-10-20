<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-05-29
*/
namespace CoreBundle\Util;

/**
 * 规范功能
 *
 */
class Canonicalizer implements CanonicalizerInterface
{
	/**
	 * 转换成小写
	 */
    public function canonicalize($string)
    {
        return mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string));
    }
}
