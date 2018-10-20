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

class BasicAuthListener implements ListenerInterface
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function preSend(RequestInterface $request)
    {
        $request->addHeader('Authorization: Basic '.base64_encode($this->username.':'.$this->password));
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
    }
}
