<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月15日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 新闻资讯
* 
*/
class Newstest extends AbstractServiceManager
{
    protected $table = 'Newstest';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}