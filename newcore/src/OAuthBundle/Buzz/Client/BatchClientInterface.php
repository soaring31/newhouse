<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace OAuthBundle\Buzz\Client;

use OAuthBundle\Buzz\Exception\ClientException;

/**
 * A client capable of running batches of requests.
 *
 * The Countable implementation should return the number of queued requests.
 */
interface BatchClientInterface extends ClientInterface, \Countable
{
    /**
     * Processes all queued requests.
     *
     * @throws ClientException If something goes wrong
     */
    public function flush();

    /**
     * Processes zero or more queued requests.
     *
     * @throws ClientException If something goes wrong
     */
    public function proceed();
}
