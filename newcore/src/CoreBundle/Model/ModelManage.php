<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015年8月20日
*/
namespace CoreBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ModelManage extends ModelBase
{
    public function __construct(ContainerInterface $container, $table, $bundle)
    {
        parent::__construct($container, $table, $bundle);
    }
}