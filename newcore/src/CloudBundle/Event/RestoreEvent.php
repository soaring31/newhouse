<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class RestoreEvent extends Event
{
    const RESTORE_COMPLETED = 'cloud.restore_completed';

    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @param \SplFileInfo $file
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }
}
