<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace OAuthBundle\Buzz\Message\Factory;

use OAuthBundle\Buzz\Message\RequestInterface;

interface FactoryInterface
{
    public function createRequest($method = RequestInterface::METHOD_GET, $resource = '/', $host = null);
    public function createFormRequest($method = RequestInterface::METHOD_POST, $resource = '/', $host = null);
    public function createResponse();
}
