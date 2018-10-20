<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年05月29日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 楼盘 sem 配置
* 
*/
class HouseSem extends AbstractServiceManager
{
    protected $table = 'HouseSem';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}