<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月22日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 定时任务
* 
*/
class Cron extends AbstractServiceManager
{
    protected $table = 'Cron';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}