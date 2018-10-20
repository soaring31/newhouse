<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月30日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 意向
* 
*/
class Intent extends AbstractServiceManager
{
    protected $table = 'Intent';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}