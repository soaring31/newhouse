<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Processor;

use Symfony\Component\Process\ProcessUtils;

class TarProcessor extends BaseProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return '.tar';
    }

    /**
     * {@inheritdoc}
     */
    public function getCompressionCommand($archivePath, $basePath)
    {
        $tarParams = array();
        $zipParams = array();

        if (isset($this->options['compression_ratio']) && $this->options['compression_ratio'] >= 0) {
            $compression_ratio = max(min($this->options['compression_ratio'], 9), 0);
            $zipParams[] = '-'.$compression_ratio;
        }

        return sprintf('tar %s c -C %s . | gzip %s > %s',
            implode(' ', $tarParams),
            ProcessUtils::escapeArgument($basePath),
            implode(' ', $zipParams),
            ProcessUtils::escapeArgument($archivePath)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Tar';
    }
}
