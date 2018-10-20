<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Manager;

use Psr\Log\LoggerInterface;
use CloudBundle\Database\DatabaseInterface;
use CloudBundle\Database\RestorableDatabaseInterface;
use CloudBundle\Exception\MissingRestorableDatabaseException;

/**
 * Class DatabaseChain.
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class DatabaseManager
{
    /**
     * @var DatabaseInterface[] links
     */
    protected $children;

    /**
     * @var \Psr\Log\LoggerInterface logger
     */
    protected $logger;

    /**
     * @param LoggerInterface     $logger
     * @param DatabaseInterface[] $databases
     */
    public function __construct(LoggerInterface $logger, array $databases = array())
    {
        $this->logger = $logger;
        $this->children = $databases;
    }

    /**
     * Add a database to the chain.
     *
     * @param DatabaseInterface $database
     */
    public function add(DatabaseInterface $database)
    {
        $this->children[] = $database;
    }

    /**
     * Dump all databases activated.
     */
    public function dump()
    {
        foreach ($this->children as $child) {
            $this->logger->info(sprintf('[cloud-backup] Dumping %s database', $child->getName()));
            $child->dump();
        }
    }

    /**
     * @throws MissingRestorableDatabaseException
     */
    public function restore()
    {
        foreach ($this->children as $child) {
            if ($child instanceof RestorableDatabaseInterface) {
                $child->restore();
                return;
            }
        }

        throw MissingRestorableDatabaseException::create();
    }
}
