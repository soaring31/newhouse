<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Database;

interface RestorableDatabaseInterface
{
    /**
     * Restore the database with a previous dump
     */
    public function restore();
}