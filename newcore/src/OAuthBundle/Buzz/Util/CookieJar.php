<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\Buzz\Util;

use OAuthBundle\Buzz\Message\MessageInterface;
use OAuthBundle\Buzz\Message\RequestInterface;

class CookieJar
{
    protected $cookies = array();

    public function setCookies($cookies)
    {
        $this->cookies = array();
        foreach ($cookies as $cookie) {
            $this->addCookie($cookie);
        }
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Adds a cookie to the current cookie jar.
     *
     * @param Cookie $cookie A cookie object
     */
    public function addCookie(Cookie $cookie)
    {
        $this->cookies[] = $cookie;
    }

    /**
     * Adds Cookie headers to the supplied request.
     *
     * @param RequestInterface $request A request object
     */
    public function addCookieHeaders(RequestInterface $request)
    {
        foreach ($this->cookies as $cookie) {
            if ($cookie->matchesRequest($request)) {
                $request->addHeader($cookie->toCookieHeader());
            }
        }
    }

    /**
     * Processes Set-Cookie headers from a request/response pair.
     *
     * @param RequestInterface $request  A request object
     * @param MessageInterface $response A response object
     */
    public function processSetCookieHeaders(RequestInterface $request, MessageInterface $response)
    {
        foreach ($response->getHeader('Set-Cookie', false) as $header) {
            $cookie = new Cookie();
            $cookie->fromSetCookieHeader($header, parse_url($request->getHost(), PHP_URL_HOST));

            $this->addCookie($cookie);
        }
    }

    /**
     * Removes expired cookies.
     */
    public function clearExpiredCookies()
    {
      foreach ($this->cookies as $i => $cookie) {
          if ($cookie->isExpired()) {
              unset($this->cookies[$i]);
          }
      }

      // reset array keys
      $this->cookies = array_values($this->cookies);
    }
}
