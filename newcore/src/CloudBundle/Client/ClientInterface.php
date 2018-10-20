<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Client;

/**
 * Class ClientInterface.
 *
 * @author  Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface ClientInterface
{
    /**
     * Upload a file to the cloud client.
     *
     * @param string $archive
     *
     * @return bool
     */
    public function upload($archive);

    /**
     * The name of the client.
     *
     * @return string
     */
    public function getName();
}
