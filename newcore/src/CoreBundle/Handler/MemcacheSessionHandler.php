<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年2月25日
 */

namespace CoreBundle\Handler;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler as BasePdoSessionHandler;

class MemcacheSessionHandler extends BasePdoSessionHandler
{
    /**
     * Constructor.
     *
     * List of available options:
     *  * prefix: The prefix to use for the memcache keys in order to avoid collision
     *  * expiretime: The time to live in seconds
     *
     * @param \Memcache $memcache A \Memcache instance
     * @param array $options An associative array of Memcache options
     *
     * @throws \InvalidArgumentException When unsupported options are passed
     */
    public function __construct(\Memcache $memcache, array $options = array())
    {
        parent::__construct($memcache, $options);
    }

    /**
     * 设置session空闲失效间隔
     */
    public function setMaxLifeTime($maxlifetime)
    {
        ini_set('session.gc_maxlifetime', $maxlifetime);
        parent::gc($maxlifetime);
    }
}