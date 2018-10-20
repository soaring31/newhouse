<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\Exception;

class MissingRestorableDatabaseException extends \LogicException
{
    /**
     * @return MissingRestorableDatabaseException
     */
    public static function create()
    {
        return new self('No restorable database is registered.');
    }
}
