<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/5/3
 * Time: 13:39
 */

namespace HouseBundle\Handler;

use Doctrine\DBAL\Connection;

/**
 * 同步数据 handler
 *
 * Class SyncHandler
 * @package HouseBundle\Handler
 */
class SyncHandler extends HandlerBase
{
    private $houseConnection;

    public function __construct(Connection $houseConnection)
    {
        $this->houseConnection = $houseConnection;
    }

    /**
     * 同步楼盘信息
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @throws \Exception
     */
    public function syncHousesInfo()
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $city_array = ['bj' => 1, 'sh' => 9, 'gz' => 231, 'sz' => 233, 'cd' => 272, 'cq' => 22, 'sjz' => 36, 'zh' => 234, 'su' => 112, 'wx' => 109, 'xa' => 325, 'nj' => 108, 'zz' => 186, 'km' => 302, 'wh' => 203, 'heb' => 93, 'dl' => 71, 'jn' => 169];

        foreach ($city_array as $area => $areaId) {
            $url = sprintf("http://newhouse.dapi.zhugefang.com/%s/newhouse/detail/complexinfo", $area);

            // 获取接口中所有楼盘的数据
            $info = $this->httpUnit->post($url, json_encode([
                "page" => [
                    "index" => 1,
                    "size"  => 1
                ]
            ]));

            $info = json_decode($info, true);
            echo "<pre>";

            if ($info['code'] == 200) {
                // 获取总条数
                $total = $info['data']['page']['total'];
                $this->logger->info("更新 $area 地区开始 总数据 $total 条数据");

                $totalProcess = 0;

                $limit      = 100;
                $totalPages = ceil($total / $limit);
                $this->logger->info(sprintf('%s 总楼盘数量为 %d; 每页取 %s 条数据', $area, $total, $limit));

                for ($i = 1; $i <= $totalPages; $i++) {
                    $pageInfo = ['page' => ['index' => $i, 'size' => $limit]];
                    $houses   = $this->httpUnit->post($url, json_encode($pageInfo));
                    $houses   = json_decode($houses, true);
                    if ($houses['code'] == 200) {
                        $detail     = $houses['data']['data'];
                        $complexIds = [];
                        $sldzs      = [];   // 售楼地址
                        $kpdates    = [];   // 开盘时间
                        $hotlines   = [];   // 热线
                        foreach ($detail as $house) {
                            // 热线取第一个
                            $hotLine = current(array_unique(array_filter(explode('#', $house['hot_line']))));

                            $complexId = $house['complex_id'];
                            array_push($complexIds, $complexId);
                            $sldzs[$complexId]    = $house['salesoffice_address'];
                            $kpdates[$complexId]  = date('Y-m-d H:i:s', $house['firstnew_saletime']);
                            $hotlines[$complexId] = $hotLine;
                        }

                        // 获取所有的楼盘
                        $newHouses = $this->houseConnection
                            ->executeQuery(sprintf("SELECT * from `cms_houses` WHERE `complex_id` IN (%s) AND `area` = %s", implode(',', $complexIds), $areaId))
                            ->fetchAll();

                        foreach ($newHouses as $newHouse) {
                            $totalProcess += 1;
                            // 更新 cms_houses
                            $houseSldzSql = sprintf("
                          UPDATE `cms_houses` 
                          SET 
                          `sldz` = '%s' , 
                          `kpdate` = '%s',
                          `hot_line` = '%s'
                          WHERE 
                          `id` = %s",
                                $sldzs[$newHouse['complex_id']],
                                $kpdates[$newHouse['complex_id']],
                                $hotlines[$newHouse['complex_id']],
                                $newHouse['id']);

                            if ($this->houseConnection->exec($houseSldzSql)) {
                                $this->logger->info(sprintf('cms_houses 更新 id 为 %s 成功', $newHouse['id']));
                            } else {
                                $this->logger->info(sprintf('cms_houses 更新 id 为 %s 成功', $newHouse['id']));
                            }

                            // 更新 cms_houses_attr
                            $houseAttrSql = sprintf("UPDATE `cms_houses_attr` SET `value` = '%s' WHERE `name` = '%s' AND `mid` = %s", $sldzs[$newHouse['complex_id']], 'sldz', $newHouse['id']);
                            if ($this->houseConnection->exec($houseAttrSql)) {
                                $this->logger->info(sprintf('cms_houses_attr 更新 mid 为 %s 成功', $newHouse['id']));
                            } else {
                                $this->logger->error(sprintf('cms_houses_attr 更新 mid 为 %s 失败', $newHouse['id']));
                            }
                        }
                    }
                }

                $this->logger->info("更新 $area 地区结束 总共处理 $totalProcess 条数据");
            }

            echo "</pre>";
        }
    }
}