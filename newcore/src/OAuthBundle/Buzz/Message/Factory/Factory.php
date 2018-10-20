<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace OAuthBundle\Buzz\Message\Factory;

use OAuthBundle\Buzz\Message\Request;
use OAuthBundle\Buzz\Message\Response;
use OAuthBundle\Buzz\Message\RequestInterface;
use OAuthBundle\Buzz\Message\Form\FormRequest;

class Factory implements FactoryInterface
{
    public function createRequest($method = RequestInterface::METHOD_GET, $resource = '/', $host = null)
    {
        return new Request($method, $resource, $host);
    }

    public function createFormRequest($method = RequestInterface::METHOD_POST, $resource = '/', $host = null)
    {
        return new FormRequest($method, $resource, $host);
    }

    public function createResponse()
    {
        return new Response();
    }
}
