<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年08月26日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 楼盘价格
 *
 */
class HousesPrice extends AbstractServiceManager
{
    protected $table = 'HousesPrice';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data, $info = null, $isValid = true)
    {
        $result = parent::add($data, $info, $isValid);

        self::updHouse(null, $result);

        return $result;
    }

    public function update($id, array $data, $info = null, $isValid = true)
    {
        $result = parent::update($id, $data, $info, $isValid);

        self::updHouse(null, $result);

        return $result;
    }

    public function delete($id, $info = null)
    {
        $result = parent::delete($id, $info);

        self::updHouse($id);

        return $result;
    }

    /**
     * 更新 houses 的均价，最高价，最低价等
     * @param unknown $data
     */
    private function updHouse($id, $info = null)
    {
        $map = array();
        if (is_object($info)) {
            $map['aid'] = is_array($info) ? $info['aid'] : $info->getAid();
        } else {
            $result = parent::getTrashById($id);
            $map['aid'] = is_array($result) ? $result['aid'] : $result->getAid();
        }
        $result = parent::findOneBy($map, array('jtime' => 'DESC', 'update_time' => 'DESC'), false);

        $result1 = $this->get('house.houses')->findOneBy(array('id' => $map['aid']), array(), false);

        if ($result) {
            $result1->setDj($result->getDj());
            $result1->setJgjj($result->getJgjj());
            $result1->setJdjj($result->getJdjj());
            $result1->setTdj($result->getTdj());
            $result1->setBdsm($result->getBdsm());
        } else {
            $result1->setDj(0);
            $result1->setJgjj(0);
            $result1->setJdjj(0);
            $result1->setTdj(0);
            $result1->setBdsm('');
        }

        $this->get('house.houses')->update($map['aid'], array(), $result1, false);
    }

    public function getPriceBi($start, $end, $areaId)
    {
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('sum(p.dj) as pirce_avg')
            ->from($this->table, 'p')
            ->where("p.aid = {$areaId} AND p.jtime >= '$start' AND p.jtime <= '$end'")
            ->getQuery();
        return $query->getResult();
    }

    public function getShangquanPrice($start, $end, $aid)
    {
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('avg(p.dj) as pirce_avg')
            ->from($this->table, 'p')
            ->where("p.aid in({$aid}) AND p.jtime >= '$start' AND p.jtime <= '$end'")
            ->getQuery();
        return $query->getResult();
    }
    public function getAreaPrice($start,$end,$allid){
        $allid=trim($allid,',');
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('sum(p.dj) as sumprice,count(p.dj) as countprice')
            ->from($this->table, 'p')
            ->where("p.aid in({$allid}) AND p.jtime >= '$start' AND p.jtime <= '$end'")
            ->getQuery();
        return $query->getResult();

    }
    public function getHouseJiaGe($start,$end,$lid){
        $qb = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('sum(p.dj) as sumprice,count(p.dj) as countprice')
            ->from($this->table, 'p')
            ->where("p.aid =$lid AND p.jtime >= '$start' AND p.jtime <= '$end'")
            ->getQuery();
        return $query->getResult();

    }
}