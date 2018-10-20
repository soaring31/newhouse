<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月25日
*/
namespace CoreBundle\Handler;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler as BasePdoSessionHandler;

class PdoSessionHandler extends BasePdoSessionHandler
{
    public function __construct($pdoOrDsn = null, array $options = array())
    {
        parent::__construct($pdoOrDsn, $options);
    }
    
    /**
     * 设置session空闲失效间隔
     */
    public function setMaxLifeTime($maxlifetime)
    {
        ini_set('session.gc_maxlifetime', $maxlifetime);
        parent::gc($maxlifetime);
    }
}