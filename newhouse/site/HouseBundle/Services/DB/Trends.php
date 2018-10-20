<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年04月07日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 楼盘动态
* 
*/
class Trends extends AbstractServiceManager
{
    protected $table = 'Trends';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}