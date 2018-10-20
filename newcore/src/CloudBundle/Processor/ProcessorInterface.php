<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Processor;

/**
 * Interface ProcessorInterface.
 *
 * @author  Jonathan Dizdarevic <dizda@dizda.fr>
 */
interface ProcessorInterface
{
    /**
     * Get compression command.
     *
     * @param string $archivePath
     * @param string $basePath
     *
     * @return string
     */
    public function getCompressionCommand($archivePath, $basePath);

    /**
     * Get file extension (with leading dot).
     *
     * @return string
     */
    public function getExtension();

    /**
     * The name of the processor.
     *
     * @return string
     */
    public function getName();
}
