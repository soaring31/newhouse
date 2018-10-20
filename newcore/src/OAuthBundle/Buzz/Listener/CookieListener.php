<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace OAuthBundle\Buzz\Listener;

use OAuthBundle\Buzz\Message\RequestInterface;
use OAuthBundle\Buzz\Message\MessageInterface;
use OAuthBundle\Buzz\Util\Cookie;
use OAuthBundle\Buzz\Util\CookieJar;

class CookieListener implements ListenerInterface
{
    private $cookieJar;

    public function __construct()
    {
        $this->cookieJar = new CookieJar();
    }

    public function setCookies($cookies)
    {
        $this->cookieJar->setCookies($cookies);
    }

    public function getCookies()
    {
        return $this->cookieJar->getCookies();
    }

    /**
     * Adds a cookie to the current cookie jar.
     *
     * @param Cookie $cookie A cookie object
     */
    public function addCookie(Cookie $cookie)
    {
        $this->cookieJar->addCookie($cookie);
    }

    public function preSend(RequestInterface $request)
    {
        $this->cookieJar->clearExpiredCookies();
        $this->cookieJar->addCookieHeaders($request);
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        $this->cookieJar->processSetCookieHeaders($request, $response);
    }
}