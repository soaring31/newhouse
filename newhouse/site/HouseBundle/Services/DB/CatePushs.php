<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月25日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 推送位分类
* 
*/
class CatePushs extends AbstractServiceManager
{
    protected $table = 'CatePushs';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
}