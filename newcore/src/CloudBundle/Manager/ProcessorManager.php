<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Manager;

use Symfony\Component\Process\Process;
use CloudBundle\Splitter\BaseSplitter;
use Symfony\Component\Filesystem\Filesystem;
use CloudBundle\Processor\ProcessorInterface;
use CloudBundle\Processor\UncompressableProcessorInterface;
use CloudBundle\Exception\UncompressionNotSupportedException;

/**
 * A ProcessorManager handles the compression, cleanup etc for a specific processor.
 *
 * @author Tobias Nyholm
 */
class ProcessorManager
{
    /**
     * @var CloudBundle\Processor\ProcessorInterface processor
     */
    protected $processor;

    /**
     * This is the path to the latest created archive.
     *
     * @var string archivePath
     */
    protected $archivePath;

    /**
     * @var array folders
     */
    protected $folders;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem filesystem
     */
    protected $filesystem;

    /**
     * @var string rootPath
     */
    protected $rootPath;

    /**
     * @var string outputPath
     */
    protected $outputPath;

    /**
     * @var string compressedArchivePath
     */
    protected $compressedArchivePath;

    /**
     * @var string filePrefix
     */
    protected $filePrefix;

    /**
     * @var array properties
     */
    protected $properties;

    /**
     * @var BaseSplitter slitter
     */
    protected $splitter;

    /**
     * @var string
     */
    protected $restoreFolder;

    /**
     * @param string $rootPath   Path to root folder
     * @param string $outputPath Path to folder with archived files
     * @param string $filePrefix Prefix for archive file (e.g. sitename)
     * @param array  $properties Date function format
     * @param array  $folders    Array of folders to archive (relative to $rootPath)
     * @param string $restoreFolder Path to restore folder
     */
    public function __construct($rootPath, $outputPath, $filePrefix, $properties, array $folders = array(), $restoreFolder = null)
    {
        $this->rootPath   = $rootPath;
        $this->outputPath = $outputPath;
        $this->filePrefix = $filePrefix;
        $this->folders    = $folders;
        $this->properties = $properties;
        $this->compressedArchivePath = $this->outputPath.'../backup_compressed/';
        $this->restoreFolder = $restoreFolder;

        $this->filesystem = new Filesystem();
    }

    /**
     * @param CloudBundle\Processor\ProcessorInterface $processor
     *
     * @return $this
     */
    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * Make a copy of all folders specified in config.
     */
    public function copyFolders()
    {
        // Copy folder for compression file
        foreach ($this->folders as $folder) {
            $this->filesystem->mirror($this->rootPath.'/'.$folder, $this->outputPath.'folders/'.$folder);
        }
    }

    /**
     * Compress to file with name like : hostname_2013-01-12_00-06-40.tar.
     */
    public function compress()
    {
        $this->archivePath = $this->compressedArchivePath . $this->buildArchiveFilename();

        $archive = $this->processor->getCompressionCommand($this->archivePath, $this->outputPath);

        $this->filesystem->mkdir($this->compressedArchivePath);
        $this->filesystem->mkdir($this->outputPath);
        $this->execute($archive);

        if ($this->splitter !== null) {
            $this->split();
        }
    }

    /**
     * @param \SplFileInfo $file
     */
    public function uncompress(\SplFileInfo $file)
    {
        if (!$this->processor instanceof UncompressableProcessorInterface) {
            throw new UncompressionNotSupportedException(
                sprintf('Uncompression is not supported for %s.', $this->processor->getName())
            );
        }

        $this->archivePath = $this->compressedArchivePath . $this->buildArchiveFilename();
        $archive = $this->processor->getUncompressCommand($this->restoreFolder, $file->getPathname(), $this->restoreFolder);
        $this->execute($archive);
    }

    /**
     * Return the archive file name.
     *
     * @return string
     */
    public function buildArchiveFilename()
    {
        return $this->filePrefix . '_' . date($this->properties['date_format']) . $this->processor->getExtension();
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
        $process = new Process($command);
        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * Return path of the archive.
     *
     * @return array
     */
    public function getArchivePath()
    {
        if (!is_array($this->archivePath)) {
            return array($this->archivePath);
        }

        return $this->archivePath;
    }

    /**
     * Remove all dirs with files.
     */
    public function cleanUp()
    {
        $this->filesystem->remove($this->compressedArchivePath);
        $this->filesystem->remove($this->outputPath);
    }

    /**
     * Here is the split.
     */
    private function split()
    {
        $this->splitter->setArchivePath($this->archivePath);
        $this->splitter->executeSplit();
        $this->archivePath = $this->splitter->getSplitFiles();
    }

    /**
     * @param CloudBundle\Splitter\BaseSplitter $splitter
     *
     * @return $this
     */
    public function setSplitter(BaseSplitter $splitter)
    {
        $this->splitter = $splitter;

        return $this;
    }

    /**
     * Allow folders to be set after construction
     *
     * @param  array  $folders  Folders to set - Defaults to empty array
     *
     * @return $this
     */
    public function setFolders(array $folders = array())
    {
        $this->folders = $folders;

        return $this;
    }

    /**
     * Add a single folder for backup
     *
     * @param  string  $folder  Folder to add
     *
     * @return $this
     */
    public function addFolder($folder)
    {
        $this->folders[] = $folder;

        return $this;
    }

    /**
     * Allow file prefix to be set after construction
     *
     * @param  string  $filePrefix  Prefix to set
     *
     * @return $this
     */
    public function setFilePrefix($filePrefix)
    {
        $this->filePrefix = $filePrefix;

        return $this;
    }

    /**
     * Set a custom date format
     *
     * @param  string  $format  A valid date format as described at php.net/date
     *
     * @return $this
     */
    public function setDateFormat($format)
    {
        $this->properties['date_format'] = $format;

        return $this;
    }

    public function getName()
    {
        return $this->processor->getName();
    }
}
