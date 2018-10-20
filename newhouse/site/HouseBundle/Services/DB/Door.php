<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年07月14日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 户型
 *
 */
class Door extends AbstractServiceManager
{
    protected $table = 'Door';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 获取楼栋户型的数据
     * 王晓宇
     * @param integer $doorId 户型ID
     *
     * @return array
     */
    public function getBuildDoor($doorId)
    {
        $qb    = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('p.id,p.name,p.dj,p.mj,p.shi,p.ting,p.wei,p.chu,p.yangtai,p.cate_type,p.cate_status,p.thumb,p.reference_totalprice')
            ->from($this->table, 'p')
            ->where("p.is_delete = :isDelete AND p.id in ( :doorId ) ")
            ->setParameter('doorId', $doorId)
            ->setParameter('isDelete', 0)
            ->orderBy('p.mj', 'desc')
            ->getQuery();

        return $query->getResult();
    }


    /**
     * 获取户型所有的状态 id集合
     *
     * @return array
     */
    public function getCateStatusIds()
    {
        $statusIds = [];

        if ($allDoorStatus = $this->findBy([], [], 20000, 1, 'cate_status')) {
            foreach ($allDoorStatus['data'] as $v) {
                array_push($statusIds, $v->getCateStatus());
            }
            $statusIds = array_unique(array_filter($statusIds));
        };

        return $statusIds;
    }

    /**
     * 获取户型的居室和数量
     * 王晓宇
     * @param integer $aid 楼盘ID
     *
     * @return array
     */
    public function getDoorSum($aid)
    {
        $qb    = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('p.shi,count(p) as counter')
            ->from($this->table, 'p')
            ->where("p.is_delete = 0 AND p.aid = :aid")
            ->setParameter('aid', $aid)
            ->addGroupBy('p.shi')
            ->getQuery();

        return $query->getResult();
    }
}