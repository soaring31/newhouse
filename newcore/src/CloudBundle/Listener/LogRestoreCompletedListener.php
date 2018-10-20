<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Listener;

use Psr\Log\LoggerInterface;
use CloudBundle\Event\RestoreEvent;

class LogRestoreCompletedListener
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
     * @param RestoreEvent $event
     */
    public function whenRestoreIsCompleted(RestoreEvent $event)
    {
        $this->logger->info(sprintf('[cloud-backup] Restoring %s is completed', $event->getFile()->getFilename()));
    }
}
