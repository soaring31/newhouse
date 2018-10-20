<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-4-21
*/
namespace CoreBundle\Services\Encoder;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * 密码加密算法
 */
class PasswordEncoder extends BasePasswordEncoder
{
	private $cost;

	public function __construct( $cost)
	{
		$cost = intval( $cost);
		if( $cost < 5 || $cost > 31 )
		{
			throw new \InvalidArgumentException('Cost too long , it must be in the range of 5-31');
		}
		$this->cost = sprintf('%02d' , $cost);
	}

	public function encodePassword( $raw , $salt = null )
	{
		if( $this->isPasswordTooLong($raw) )
		{
			throw new BadCredentialsException('Invalid password.');
		}
		return md5( md5( $raw ) . $salt );
	}

	public function isPasswordValid($encoded, $raw, $salt = null)
	{
		if ($this->isPasswordTooLong($raw))
		{
			return false;
		}

		return md5( md5( $raw).$salt) === $encoded;
	}
}