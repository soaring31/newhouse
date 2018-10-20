<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth;

interface RequestDataStorageInterface
{
    /**
     * Fetch a request data from the storage.
     *
     * @param ResourceOwnerInterface $resourceOwner
     * @param string                 $key
     * @param string                 $type
     *
     * @return array
     */
    public function fetch(ResourceOwnerInterface $resourceOwner, $key, $type = 'token');

    /**
     * Save a request data to the storage.
     *
     * @param ResourceOwnerInterface $resourceOwner
     * @param array|string           $value
     * @param string                 $type
     */
    public function save(ResourceOwnerInterface $resourceOwner, $value, $type = 'token');
}
