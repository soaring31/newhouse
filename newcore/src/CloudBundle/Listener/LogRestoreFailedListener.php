<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Listener;

use Psr\Log\LoggerInterface;
use CloudBundle\Event\RestoreFailedEvent;

class LogRestoreFailedListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param RestoreFailedEvent $event
     */
    public function whenRestoreIsFailed(RestoreFailedEvent $event)
    {
        $this->logger->critical('[cloud-backup] Restoring database failed', array('exception' => $event->getException()));
    }
}
