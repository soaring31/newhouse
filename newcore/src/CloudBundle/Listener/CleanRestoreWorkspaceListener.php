<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Listener;

use CloudBundle\Event\RestoreEvent;
use CloudBundle\Event\RestoreFailedEvent;
use Symfony\Component\Filesystem\Filesystem;

class CleanRestoreWorkspaceListener
{
    /**
     * @var string
     */
    private $restoreFolder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param string $restoreFolder
     * @param Filesystem $filesystem
     */
    public function __construct($restoreFolder, Filesystem $filesystem)
    {
        $this->restoreFolder = $restoreFolder;
        $this->filesystem = $filesystem;
    }

    /**
     * @param RestoreEvent $event
     */
    public function whenRestoreIsCompleted(RestoreEvent $event)
    {
        $this->clean();
    }

    /**
     * @param RestoreFailedEvent $event
     */
    public function whenRestoreIsFailed(RestoreFailedEvent $event)
    {
        $this->clean();
    }

    private function clean()
    {
        $this->filesystem->remove($this->restoreFolder);
    }
}
