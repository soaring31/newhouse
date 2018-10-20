<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Manager;

use Psr\Log\LoggerInterface;
use CloudBundle\Client\ClientInterface;
use CloudBundle\Client\DownloadableClientInterface;
use CloudBundle\Exception\MissingDownloadableClientsException;


/**
 * Class ClientChain.
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class ClientManager
{
    /**
     * @var ClientInterface[] children
     */
    protected $children;

    /**
     * @var \Psr\Log\LoggerInterface logger
     */
    protected $logger;

    /**
     * @param LoggerInterface   $logger
     * @param ClientInterface[] $clients
     */
    public function __construct(LoggerInterface $logger, array $clients = array())
    {
        $this->logger = $logger;
        $this->children = $clients;
    }

    /**
     * Add a client to the chain.
     *
     * @param ClientInterface $client
     */
    public function add(ClientInterface $client)
    {
        $this->children[] = $client;
    }

    /**
     * Upload to all active clients.
     *
     * @param array $files is an array with file paths
     */
    public function upload($files)
    {
        $exception = null;

        //for each client
        foreach ($this->children as $child) {
            $this->logger->info(sprintf('[cloud-backup] Uploading to %s', $child->getName()));

            try {
                //try to upload every file, one at a time
                foreach ($files as $file) {
                    $child->upload($file);
                }
            } catch (\Exception $e) {
                //save the exception for later, there might be other children that are working
                $exception = $e;
            }
        }

        if ($exception) {
            throw $exception;
        }
    }

    /**
     * @return \SplFileInfo
     *
     * @throws MissingDownloadableClientsException
     */
    public function download()
    {
        foreach ($this->children as $child) {
            if ($child instanceof DownloadableClientInterface) {
                return $child->download();
            }
        }

        throw MissingDownloadableClientsException::create();
    }
}
