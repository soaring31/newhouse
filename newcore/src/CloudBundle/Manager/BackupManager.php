<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Manager;

use Psr\Log\LoggerInterface;
use CloudBundle\Event\BackupEvent;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BackupManager
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var CloudBundle\Manager\DatabaseManager
     */
    private $databaseManager;

    /**
     * @var CloudBundle\Manager\ClientManager
     */
    private $clientManager;

    /**
     * @var CloudBundle\Manager\ProcessorManager
     */
    private $processorManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param LoggerInterface          $logger
     * @param DatabaseManager          $databaseManager
     * @param ClientManager            $clientManager
     * @param ProcessorManager         $processorManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(LoggerInterface $logger,DatabaseManager $databaseManager,
        ClientManager $clientManager, ProcessorManager $processorManager, EventDispatcherInterface $eventDispatcher
    ) {
        $this->logger = $logger;
        $this->databaseManager = $databaseManager;
        $this->clientManager = $clientManager;
        $this->processorManager = $processorManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Start the backup.
     *
     * @return bool
     */
    public function execute()
    {
        $successful = true;
        try {
            // Dump all databases
            $this->databaseManager->dump();

            // Backup folders if specified
            $this->logger->info('[cloud-backup] Copying folders.');
            $this->processorManager->copyFolders();

            // Compress everything
            $this->logger->info(sprintf('[cloud-backup] Compressing to archive using %s', $this->processorManager->getName()));
            $this->processorManager->compress();

            // Transfer with all clients
            $this->clientManager->upload($this->processorManager->getArchivePath());
        } catch (\Exception $e) {
            // Write log
            $this->logger->critical('[cloud-backup] Unexpected exception.', array('exception' => $e));

            $successful = false;
        }

        try {
            // If we catch an exception or not, we would still like to try cleaning up after us
            $this->logger->info('[cloud-backup] Cleaning up after us.');
            $this->processorManager->cleanUp();
        } catch (IOException $e) {
            $this->logger->error('[cloud-backup] Cleaning up failed.', array('exception' => $e));

            return false;
        }

        if ($successful) {
            $this->eventDispatcher->dispatch(BackupEvent::BACKUP_COMPLETED, new BackupEvent());
        }

        return $successful;
    }

    /**
     * @deprecated
     *
     * @return ClientManager
     */
    public function getClientManager()
    {
        @trigger_error(sprintf('%s::%s is deprecated and will be removed in 4.0.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

        return $this->clientManager;
    }

    /**
     * @deprecated
     *
     * @return DatabaseManager
     */
    public function getDatabaseManager()
    {
        @trigger_error(sprintf('%s::%s is deprecated and will be removed in 4.0.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

        return $this->databaseManager;
    }

    /**
     * @deprecated
     *
     * @return ProcessorManager
     */
    public function getProcessorManager()
    {
        @trigger_error(sprintf('%s::%s is deprecated and will be removed in 4.0.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

        return $this->processorManager;
    }
}
