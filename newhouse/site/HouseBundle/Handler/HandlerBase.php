<?php

namespace HouseBundle\Handler;

use HouseBundle\Utils\HttpUtils;
use Psr\Log\LoggerInterface;

abstract class HandlerBase
{
    /**
     * @var HttpUtils
     */
    protected $httpUnit;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
    }

    /**
     * @param $logger
     *
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param mixed $httpUnit
     *
     * @return $this
     */
    public function setHttpUnits($httpUnit)
    {
        $this->httpUnit = $httpUnit;

        return $this;
    }
}