<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Splitter;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @author Nick Doulgeridis
 */
abstract class BaseSplitter
{
    /**
     * @var string
     */
    private $archivePath;

    /**
     * @var integer
     */
    private $splitSize;

    /**
     * @param $splitSize
     */
    public function __construct($splitSize)
    {
        $this->splitSize = $splitSize;
    }

    /**
     * @return array
     */
    public function getSplitFiles()
    {
        $file = new File($this->archivePath);
        $finder = new Finder();
        $finder->files()->in(dirname($this->archivePath))->notName($file->getFilename())->sortByModifiedTime();

        return iterator_to_array($finder);
    }

    /**
     * @return integer
     */
    public function getSplitSize()
    {
        return $this->splitSize;
    }

    /**
     * @return string
     */
    public function getOutputFolder()
    {
        return dirname($this->archivePath);
    }

    /**
     * @return string
     */
    public function getArchivePath()
    {
        return $this->archivePath;
    }

    /**
     * @param string $archivePath
     *
     * @return self
     */
    public function setArchivePath($archivePath)
    {
        $this->archivePath = $archivePath;

        return $this;
    }

    abstract public function executeSplit();

    /**
     * @return string
     */
    abstract public function getCommand();
}
