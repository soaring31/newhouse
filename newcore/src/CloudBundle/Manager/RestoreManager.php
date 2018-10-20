<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Manager;

use CloudBundle\Event\RestoreEvent;
use CloudBundle\Event\RestoreFailedEvent;
use Symfony\Component\Filesystem\Filesystem;
use CloudBundle\Exception\RestoringNotAvailableException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class RestoreManager
{
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
     * @var string
     */
    private $restoreFolder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var bool
     */
    private $doRestore;

    /**
     * @param DatabaseManager          $databaseManager
     * @param ClientManager            $clientManager
     * @param ProcessorManager         $processorManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param string                   $restoreFolder
     * @param boolean                  $doRestore
     */
    public function __construct(
        DatabaseManager $databaseManager,
        ClientManager $clientManager,
        ProcessorManager $processorManager,
        EventDispatcherInterface $eventDispatcher,
        $restoreFolder,
        Filesystem $filesystem,
        $doRestore
    ) {
        $this->databaseManager = $databaseManager;
        $this->clientManager = $clientManager;
        $this->processorManager = $processorManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->restoreFolder = $restoreFolder;
        $this->filesystem = $filesystem;
        $this->doRestore = (boolean) $doRestore;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if (!$this->doRestore) {
            throw RestoringNotAvailableException::create();
        }

        try {
            $this->filesystem->mkdir($this->restoreFolder);
            $file = $this->clientManager->download();
            $this->processorManager->uncompress($file);
            $this->databaseManager->restore();
            $this->eventDispatcher->dispatch(RestoreEvent::RESTORE_COMPLETED, new RestoreEvent($file));

            return true;
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(RestoreFailedEvent::RESTORE_FAILED, new RestoreFailedEvent($e));
        }

        return false;
    }
}
