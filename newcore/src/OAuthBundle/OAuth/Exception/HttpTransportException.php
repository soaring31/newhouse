<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\OAuth\Exception;

class HttpTransportException extends \RuntimeException
{
    private $ownerName;

    public function __construct($message, $ownerName, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->ownerName = $ownerName;
    }

    public function getOwnerName()
    {
        return $this->ownerName;
    }
}
