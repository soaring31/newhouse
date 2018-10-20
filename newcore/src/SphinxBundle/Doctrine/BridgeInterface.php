<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-03-28
 */
namespace SphinxBundle\Doctrine;

/**
 * Doctrine bridge interface
 */
interface BridgeInterface
{
    /**
     * Add entity list to sphinx search results
     * @param  array  $results Sphinx search results
     * @param  string $index   Index name
     * @return array
     */
    public function parseResults(array $results, $index);
}
