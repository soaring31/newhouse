<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年6月28日
 */

namespace HouseBundle\Command;

use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use HouseBundle\Form\Api\HouseSemType;
use HouseBundle\Utils\HttpUtils;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 获取sem手机号
 * @author Administrator
 *
 */
class SyncSemTelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sync:sem')
            ->addArgument('type', InputArgument::OPTIONAL, 'add|del')
            ->setDescription('获取400电话(sem电话)')
            ->setHelp('获取400 或者 sem电话信息');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        switch ($input->getArgument('type')) {
            case 'add':
                return $this->addSemTel();
                break;
            case 'del':
                return $this->addSemTel(2);
                break;
            default:
                dump('命令错误');
        }
    }

    /**
     * @param int $addType 1: 添加 2: 解绑
     */
    private function addSemTel($addType = 1)
    {
        $newBindSemKey = $addType == 1 ? 'new_bind_sem' : 'del_bind_sem';

        if (!$houseSems = $this->getCache()->fetch($newBindSemKey)) {
            dump('同步数据不存在');
            die;
        }

        // 获取当前同步所有的城市
        $houseIds    = [];
        $houseSemIds = [];
        foreach ($houseSems as $houseSem) {
            array_push($houseIds, $houseSem->getHouseId());
            array_push($houseSemIds, $houseSem->getId());
        }

        $houseSemsDic = $this->getDictionary($houseSems);
        $houseQb      = $this->getEntityManager()->createQueryBuilder();

        $houses = $houseQb
            ->select('p')
            ->from('HouseBundle\Entity\Houses', 'p')
            ->where(sprintf('p.id IN (%s)', implode(',', array_unique($houseIds))))
            ->getQuery()
            ->getResult();

        $areaIds = [];
        foreach ($houses as $house) {
            array_push($areaIds, $house->getArea());
        }

        $areaQb         = $this->getEntityManager()->createQueryBuilder();
        $authGroupAreas = $areaQb
            ->select('p')
            ->from('HouseBundle\Entity\AuthGroupArea', 'p')
            ->where(sprintf('p.rulesarea IN (%s)', implode(',', array_unique($areaIds))))
            ->getQuery()
            ->getResult();

        $cityInfos = [];
        foreach ($authGroupAreas as $authGroupArea) {
            $cityInfos[$authGroupArea->getRulesarea()] = $authGroupArea;
        }

        $houses = $this->getDictionary($houses);
        foreach ($houses as &$house) {
            $house->authGroupArea = $cityInfos[$house->getArea()];
        }

        $items = [];
        foreach ($houseSems as $houseSem) {
            $houseId     = $houseSem->getHouseId();
            $houseDetail = $houses[$houseId];

            switch ($houseSem->getType()) {
                case 1: // sem
                    $platform = strtolower(HouseSemType::PLATFORMS[$houseSem->getPlatform()]);
                    break;
                case 2: // dsp
                    $platform = 0;
                    break;
                default:
                    $platform = 0;
            }

            $items[$houseSem->getId()] = [
                'complex_id'   => $houseDetail->getId(),
                'called'       => $houseDetail->getTel(),
                'bind_type'    => 1,
                'complex_name' => $houseDetail->getName(),
                'cityarea_id'  => $houseDetail->getRegion(),
                'city'         => $houseDetail->authGroupArea->getCity(),
                'from'         => strtolower(HouseSemType::TYPES[$houseSem->getType()]),
                'platform'     => $platform,
                'channel'      => $houseSem->getChannel(),
            ];
        }

        if ($addType == HouseSemType::STATUS_OK) {
            $this->bindSem($items, $houseSemsDic);
        } else {
            // 解绑
            $this->unbindSem($items);
        }
    }

    /**
     * @param array $items
     * @param array $houseSemsDic
     *
     * @return string
     */
    private function bindSem(array $items, array $houseSemsDic)
    {
        $url    = 'http://csr.zhugefang.com/api/Ji_Ya_Communicate/addJiyaBind';
        $result = [];
        $semIds = [];
        try {
            foreach ($items as $semId => $item) {
                $semItem = $houseSemsDic[$semId];
                $this->getLogger()->info('添加楼盘sem 电话 请求数据', $item);

                $addRes = $this->getHttpUnit()->post($url, $item);

                $this->getLogger()->info('添加楼盘sem 电话 请求结果', [$addRes]);
                $addRes = json_decode($addRes, true);
                $info   = $addRes['data'];

                if ($info['code'] == 200) {
                    if (!empty($info['bigcodetel']) && !empty($info['extcode'])) {

                        $type     = $semItem->getType();
                        $channels = $type == 1 ? HouseSemType::SEM_CHANNELS : HouseSemType::DSP_CHANNELS;

                        $info               = [
                            'sem_id'       => $semId,
                            'house_id'     => $semItem->getHouseId(),
                            'sem_type'     => HouseSemType::TYPES[$semItem->getType()],
                            'sem_platform' => HouseSemType::PLATFORMS[$semItem->getPlatform()],
                            'sem_channel'  => $channels[$semItem->getChannel()],
                            'bigcodetel'   => $info['bigcodetel'],
                            'extcode'      => $info['extcode'],
                            'status'       => HouseSemType::STATUS_OK,
                            'checked'      => 1,
                            'attributes'   => '',
                            'sort'         => 0,
                            'issystem'     => 0,
                            'identifier'   => '',
                            'create_time'  => time(),
                            'update_time'  => time(),
                            'is_delete'    => 0,
                        ];
                        $info['identifier'] = md5(json_encode($info));

                        $querySql = sprintf('SELECT * FROM `cms_house_sem_tel` WHERE `sem_id` = %d', $semId);
                        $this->getConnection()->fetchAll($querySql);
                        if ($exitSemTem = $this->getConnection()->fetchAll($querySql)) {
                            $exitSemTem = current($exitSemTem);

                            // 更新
                            $str = '';
                            foreach ($info as $k => $v) {
                                $str .= sprintf(",`%s`='%s'", $k, $v);
                            }
                            $sql = sprintf("UPDATE `cms_house_sem_tel` SET %s WHERE `id` = %d", ltrim($str, ','), $exitSemTem['id']);

                        } else {
                            // 添加
                            $keys = implode(',', array_map(function ($key) {
                                return '`' . $key . '`';
                            }, array_keys($info)));

                            $values = implode(',', array_map(function ($key) {
                                return sprintf("'%s'", $key);
                            }, $info));

                            $sql = sprintf("INSERT INTO `cms_house_sem_tel` (%s) VALUES (%s)", $keys, $values);
                        }

                        if ($execRes = $this->getConnection()->exec($sql)) {
                            // 更新 house_sem 表状态
                            array_push($semIds, $semId);
                            array_push($result, [
                                'result' => $execRes,
                                'sem_id' => $semId
                            ]);
                        }
                    }
                } else {
                    $this->getLogger()->error('获取 sem 电话 接口错误', $addRes);
                }
            }

            // 更新house_sem 表
            $sql = sprintf("UPDATE `cms_house_sem` SET status = 1 WHERE `id` in (%s)", implode(',', $semIds));
            $this->getConnection()->exec($sql);
            $this->getLogger()->info('更新 cms_house_sem 添加 sem 电话成功', $semIds);

            return 'success';

        } catch (\Exception $e) {

            dump($e->getMessage());
            dump($e->getFile());
            dump($e->getLine());

            $this->getLogger()->error('添加楼盘sem 电话出错', [
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ]);
        }
    }

    /**
     * @param array $items
     *
     * @return string
     */
    private function unbindSem(array $items)
    {
        $url        = 'http://csr.zhugefang.com/api/Ji_Ya_Communicate/delWorkGroup';
        $successIds = [];
        try {
            foreach ($items as $semId => $item) {
                $this->getLogger()->info('解绑楼盘sem 电话 请求数据', $item);
                $addRes = $this->getHttpUnit()->post($url, $item);
                $this->getLogger()->info('解绑楼盘sem 电话 请求结果', [$addRes]);
                $addRes = json_decode($addRes, true);
                $code   = $addRes['data']['code'];
                if ($code == 200 || $code == 100) {
                    // 解绑成功
                    array_push($successIds, $semId);
                } else {
                    $this->getLogger()->error('解绑楼盘sem 电话 接口错误', $addRes);
                }
            }

            // 更新house_sem_tel解绑状态
            $telSql = sprintf("UPDATE `cms_house_sem_tel` SET status = %d WHERE `sem_id` IN (%s)", HouseSemType::STATUS_UNBIND, implode(',', array_unique($successIds)));

            // 更新house_sem解绑状态
            $semSql = sprintf("UPDATE `cms_house_sem` SET status = %d WHERE `id` IN (%s)", HouseSemType::STATUS_UNBIND, implode(',', array_unique($successIds)));

            if ($this->getConnection()->exec($telSql) && $this->getConnection()->exec($semSql)) {
                $this->getLogger()->info('更新 cms_house_sem, cms_house_sem_tel 解绑 sem 电话成功', $successIds);
            }

            return 'success';

        } catch (\Exception $e) {
            $this->getLogger()->error('添加楼盘sem 电话出错', [$e->getMessage(), $e->getFile(), $e->getLine()]);
        }
    }

    /**
     * Get a dictionary keyed by primary keys.
     *
     * @param  \ArrayAccess|array|null $items
     * @return array
     */
    private function getDictionary($items = null)
    {
        $dictionary = [];

        foreach ($items as $value) {
            $dictionary[$value->getId()] = $value;
        }

        return $dictionary;
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->getContainer()->get('monolog.logger.zhuge');
    }

    /**
     * @return HttpUtils
     */
    public function getHttpUnit()
    {
        return $this->getContainer()->get('house.http.utils');
    }

    /**
     * @return MemcacheCache
     */
    private function getCache()
    {
        return $this->getContainer()->get('core.memcache');
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->getContainer()->get('doctrine.dbal.house_connection');
    }
}