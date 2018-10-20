<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Client;

use Symfony\Component\Filesystem\Filesystem as LocalFilesystem;

/**
 * Client for Flysystem adapters.
 *
 * @author Marc Aubé
 */
class FlysystemClient implements ClientInterface
{
    /**
     * @var FilesystemInterface[]
     */
    private $filesystems;

    /**
     * {@inheritdoc}
     */
    public function upload($archive)
    {
        $fileName = explode('/', $archive);

        /** @var FilesystemInterface $filesystem */
        foreach ($this->filesystems as $filesystem) {
            $filesystem->dumpFile(end($fileName), file_get_contents($archive));
        }
    }

    /**
     * Add a filesystem adapter.
     *
     * @param FilesystemInterface $filesystem
     */
    public function addFilesystem(LocalFilesystem $filesystem)
    {
        $this->filesystems[] = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Flysystem';
    }
}
