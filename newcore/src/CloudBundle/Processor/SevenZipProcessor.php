<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Processor;

class SevenZipProcessor extends BaseProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return '.7z';
    }

    /**
     * {@inheritdoc}
     */
    public function getCompressionCommand($archivePath, $basePath)
    {
        $params = array();

        if (isset($this->options['password']) && $this->options['password']) {
            $params[] = '-p"'.$this->options['password'].'"';
        }

        if (isset($this->options['compression_ratio']) && $this->options['compression_ratio'] >= 0) {
            $compression_ratio = max(min($this->options['compression_ratio'], 9), 0);

            if ($compression_ratio > 1 && ($compression_ratio % 2 == 0)) {
                $compression_ratio--;
            }

            $params[] = '-mx'.$compression_ratio;
        }

        return sprintf('cd %s && 7z a %s %s', $basePath, implode(' ', $params), $archivePath);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'SevenZip';
    }
}
