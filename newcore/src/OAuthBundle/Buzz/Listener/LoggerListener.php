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
use OAuthBundle\Buzz\Exception\InvalidArgumentException;

class LoggerListener implements ListenerInterface
{
    private $logger;
    private $prefix;
    private $startTime;

    public function __construct($logger, $prefix = null)
    {
        if (!is_callable($logger)) {
            throw new InvalidArgumentException('The logger must be a callable.');
        }

        $this->logger = $logger;
        $this->prefix = $prefix;
    }

    public function preSend(RequestInterface $request)
    {
        $this->startTime = microtime(true);
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        $seconds = microtime(true) - $this->startTime;

        call_user_func($this->logger, sprintf('%sSent "%s %s%s" in %dms', $this->prefix, $request->getMethod(), $request->getHost(), $request->getResource(), round($seconds * 1000)));
    }
}
