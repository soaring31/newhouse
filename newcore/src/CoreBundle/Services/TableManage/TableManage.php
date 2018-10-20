<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月3日
*/
namespace CoreBundle\Services\TableManage;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TableManage extends AbstractServiceManager implements TableManageInterface
{
    protected $table = 'Clonebase';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
}