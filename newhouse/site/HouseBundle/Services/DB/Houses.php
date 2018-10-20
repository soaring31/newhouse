<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年06月23日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use HouseBundle\Handler\HouseHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 楼盘
 *
 */
class Houses extends AbstractServiceManager
{
    protected $table = 'Houses';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 获取最热商圈
     *
     * @param integer $areaId 城市 id
     * @param integer $number 商圈数量
     *
     * @return array
     */
    public function getHotCircle($areaId, $number)
    {
        $qb    = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('p.area, p.cate_circle as c_id, count(p) as counter')
            ->from($this->table, 'p')
            ->where('p.area = :area AND p.cate_circle != 0 AND p.is_delete = 0')
            ->setParameter('area', $areaId)
            ->addGroupBy('p.cate_circle')
            ->orderBy('counter', 'desc')
            ->setMaxResults($number)
            ->getQuery();

        $query->useResultCache(true, 1200);

        return $query->getResult();
    }


    public function setGeohash($data)
    {
        $maps            = explode(',', $data['map']);
        $data['geohash'] = $this->get('core.common')->encode_geohash($maps[1], $maps[0]);
        return $data;
    }

    public function relationByHouse($info)
    {
        $geohash = $info->getGeohash();
        $geokey  = substr($geohash, 0, 5) . '%'; //相似度为5个字符串
        $lpmaps  = explode(',', $info->getMap());
        $data    = $this->get('house.around')->findBy(array('geohash' => array('like' => $geokey)));

        if (empty($data['data']) || count($lpmaps) < 2)
            return false;

        foreach ($data['data'] as $val) {
            $maps     = explode(',', $val->getMap());
            $distance = $this->get('core.common')->getDistance($lpmaps[1], $lpmaps[0], $maps[1], $maps[0]);
            if ($distance <= 3000) //距离小于3公里
            {
                $_data = array('aid' => $info->getId(), 'fromid' => $val->getId(), 'name' => $val->getName());
                return $this->get('house.houses_arc')->add($_data, null, false);
            }
        }
        return true;
    }

    public function getMonthHouseCount($start, $end, $areaId)
    {
        $qb    = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('count(h.id) as house_count')
            ->from($this->table, 'h')
            ->where('h.area = :area AND h.create_time >= :start AND h.create_time <= :end AND h.is_delete=:is_delete')
            ->setParameter('area', $areaId)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('is_delete', 0)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * 按月获取最新楼盘
     * 王晓宇
     * @param integer $areaId 城市 id
     * @param integer $start 开始时间
     * @param integer $end 结束时间
     * @param integer $page 页数
     * @param integer $limit 数量
     *
     * @return array
     */
    public function getNewBuild($start, $end, $areaId, $page = 0, $limit = 10)
    {
        $qb    = $this->sqlManager->getEntityManager()->createQueryBuilder();
        $query = $qb->select('p.id,p.name,p.thumb,p.thumb,p.cate_type,p.cate_status,p.dj,p.address,p.tel,p.zxcd,p.tslp,p.cate_circle,p.create_time,p.kpdate,p.kpsm,p.tag,p.region,p.checked')
            ->from($this->table, 'p')
            ->where("p.is_delete = :is_delete AND p.area = :area AND p.kpdate between :startTime AND :endTime AND p.tag = :tag AND p.checked = :checked")
            ->setParameter('area', $areaId)
            ->setParameter('startTime', $start)
            ->setParameter('endTime', $end)
            ->setParameter('is_delete', 0)
            ->setParameter('tag', 1)
            ->setParameter('checked', 1)
            ->orderBy('p.kpdate', 'desc')
            ->setFirstResult($page)
            ->setMaxResults($limit)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * 获取特色楼盘
     * 王晓宇
     * @param integer $tslpid 特色楼盘ID
     *
     * @return array
     */
    public function getTslp($tslpid)
    {
        //拆分成数组
        $tslpArray = explode(',', $tslpid);
        $tslp      = array('');
        foreach ($tslpArray as $key => $value) {
            switch ($value) {
                case 1:
                    array_push($tslp, "低总价");
                    break;
                case 2:
                    array_push($tslp, "小户型");
                    break;
                case 3:
                    array_push($tslp, "品牌地产");
                    break;
                case 4:
                    array_push($tslp, "现房");
                    break;
                case 5:
                    array_push($tslp, "地铁沿线");
                    break;
                default:
                    array_push($tslp, "暂无");
                    break;
            }
        }
        //合并成字符串
        $tslp = trim(implode(',', $tslp), ",");
        return $tslp;
    }

    /**
     * 获取楼盘环线
     * 王晓宇
     * @param integer $hxs 环线ID
     *
     * @return array
     */
    public function getHxs($hxs)
    {
        //拆分成数组
        $tslpArray = explode(',', $hxs);
        $tslp      = array('');
        foreach ($tslpArray as $key => $value) {
            switch ($value) {
                case 1:
                    array_push($tslp, "一环以内");
                    break;
                case 2:
                    array_push($tslp, "一至二环");
                    break;
                case 3:
                    array_push($tslp, "二至三环");
                    break;
                case 4:
                    array_push($tslp, "三环以内");
                    break;
                default:
                    array_push($tslp, "暂无");
                    break;
            }
        }
        //合并成字符串
        $tslp = trim(implode(',', $tslp), ",");
        return $tslp;
    }

    /**
     * @param int $id
     * @param array $data
     * @param null $entity
     * @param bool $isValid
     * @return \CoreBundle\Services\entity|\HouseBundle\Entity\Houses|void
     * @throws \CoreBundle\Services\LogicException
     * @throws \Exception
     */
    public function update($id, array $data, $entity = null, $isValid = true)
    {
        $houseHandler = $this->get('house.house.handler');

        if (!$houseHandler instanceof HouseHandler)
            return;

        // 获取原始数据
        if (!$oldHouse = $this->findOneBy(['id' => $id]))
            throw new \LogicException('数据不存在或已被删除');

        $originHouse = clone $oldHouse;

        $house = parent::update($id, $data, $entity, $isValid);

        if (!$house instanceof \HouseBundle\Entity\Houses)
            return;

        $tel       = $data['tel'];
        $originTel = $originHouse->getTel();

        switch (true) {
            case !$tel && $houseHandler->delServiceTel($originHouse):
                $house
                    ->setPcTel('')
                    ->setWebTel('')
                    ->setAppTel('');
                break;
            case !$originTel:
                $house = $houseHandler->updateServiceTel($house);
                break;
            case $tel && ($tel == $originTel):
                break;
            case $tel && $originTel && ($tel != $originTel):
                $houseHandler->delServiceTel($originHouse);
                $house = $houseHandler->updateServiceTel($house);
                break;
            default:
                return $house;
        }

        return self::modifyEntity($house);  // 写库
    }

    /**
     * @param array $data
     * @param null $entity
     * @param bool $isValid
     *
     * @return \CoreBundle\Services\entity|\HouseBundle\Entity\Houses|void
     *
     * @throws \Exception
     */
    public function add(array $data, $entity = null, $isValid = true)
    {
        $houseHandler = $this->get('house.house.handler');

        if (!$houseHandler instanceof HouseHandler)
            return;

        $house = parent::add($data, $entity, $isValid);

        if (!$house->getTel())
            return $house;

        // 更新400电话
        $house = $houseHandler->updateServiceTel($house);

        return self::modifyEntity($house);    // 写库
    }
}
