<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 团购
* 
*/
class Groupbuy extends AbstractServiceManager
{
    protected $table = 'Groupbuy';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}