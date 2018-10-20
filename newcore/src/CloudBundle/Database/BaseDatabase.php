<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Database;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class BaseDatabase.
 */
abstract class BaseDatabase implements DatabaseInterface
{
    const DB_PATH = '';

    protected $dataPath;
    protected $filesystem;
    protected $timeout;

    /**
     * Get SF2 Filesystem.
     *
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->dataPath = $basePath.static::DB_PATH.'/';
        $this->filesystem = new Filesystem();
        $this->timeout = 300;
    }

    /**
     * Handle process error on fails.
     *
     * @param string $command
     *
     * @throws \RuntimeException
     */
    protected function execute($command)
    {
        $process = new Process($command, null, null, null, $this->timeout);
        $process->run();

        if (!$process->isSuccessful())
            throw new \RuntimeException($process->getErrorOutput());
    }

    /**
     * Prepare path for dump file.
     */
    protected function preparePath()
    {
        $this->filesystem->mkdir($this->dataPath);
    }

    /**
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }
}
