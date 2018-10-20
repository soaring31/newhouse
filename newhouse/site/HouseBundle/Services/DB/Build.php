<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月19日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 楼栋
* 
*/
class Build extends AbstractServiceManager
{
    protected $table = 'Build';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    /**
     * 查询已开盘的所有楼栋
     * 王晓宇
     * @param integer $buildid 楼盘id
     * @param integer $date 当前时间
     *
     * @return array
     */
    public function getNewOpenBuild($buildid,$date){
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('p.name')
            ->from($this->table, 'p')
            ->where("p.is_delete = 0 AND p.aid = :buildid AND p.kpsj <= :kpsj")
            ->setParameters([
            	'buildid'=>$buildid,
            	'kpsj'=> $date
            ])
            ->getQuery();
        return $query->getResult();
    }
}