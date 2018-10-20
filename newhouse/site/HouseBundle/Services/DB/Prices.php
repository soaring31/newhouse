<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年10月12日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * C端-------房价走势
 *
 */
class Prices extends AbstractServiceManager
{
    protected $table = 'Prices';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    public function getPrice($start, $end, $areaId)
    {
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('AVG(p.price) as pirce_avg')
        ->from($this->table, 'p')
        ->where("p.area ={$areaId} AND p.months >= '$start' AND p.months <= '$end' AND p.is_delete = 0 ")
        ->getQuery();
        return $query->getResult();
    }
    
    public function getPriceInfo($start,$end,$areaId){
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('sum(p.price) as sumprice,count(p.price) as countprice')
        ->from($this->table, 'p')
        ->where("p.area ={$areaId} AND p.months >= '$start' AND p.months <= '$end' AND p.is_delete = 0 ")
        ->getQuery();
        return $query->getResult();
    }
    public function getPriceBi($start, $end, $areaId)
    {
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('sum(p.price) as pirce_avg,count(p.sort) as cc')
        ->from($this->table, 'p')
        ->where("p.area = {$areaId} AND p.create_time >= {$start} AND p.create_time <= {$end} AND p.is_delete = 0")
        ->getQuery();
        return $query->getResult();
    }
}