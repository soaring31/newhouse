<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月17日
*/
namespace CoreBundle\Security\ClearData;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClearData extends ServiceBase
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * 清除演示数据
     */
    public function clear()
    {
        
    }
}