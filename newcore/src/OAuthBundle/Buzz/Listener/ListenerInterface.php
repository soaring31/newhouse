<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace OAuthBundle\Buzz\Listener;

use OAuthBundle\Buzz\Message\MessageInterface;
use OAuthBundle\Buzz\Message\RequestInterface;

interface ListenerInterface
{
    public function preSend(RequestInterface $request);
    public function postSend(RequestInterface $request, MessageInterface $response);
}
