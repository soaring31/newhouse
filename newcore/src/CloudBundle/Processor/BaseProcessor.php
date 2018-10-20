<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Processor;

abstract class BaseProcessor
{
    /**
     * @var array options
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Add new options to the existing once. This may overwrite.
     *
     * @param array $options
     *
     * @return $this
     */
    public function addOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }
}
