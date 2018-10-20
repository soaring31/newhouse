<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年07月28日
 */

namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 区域
 *
 */
class Area extends AbstractServiceManager
{
    protected $table = 'Area';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);

        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name') . "_area";

        $this->filePath = $this->get('core.common')->getSiteRoot() . "Config" . DIRECTORY_SEPARATOR . "config_area.yml";
    }

    public function add(array $data, $info = null, $isValid = true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }

    public function update($id, array $data, $info = null, $isValid = true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
    }

    public function getAreas($id)
    {
        $areas = parent::findBy(array('pid' => $id));
        $data  = array($id);

        if (empty($areas))
            return $data;

        foreach ($areas['data'] as $item) {
            $data[] = $item->getId();
        }
        return $data;
    }

    protected function handleYmlData($data)
    {
        $result        = array();
        $map           = array();
        $map['status'] = 1;
        $map['order']  = 'sort|asc,id|asc';
        $info          = parent::findBy($map, null, 5000);

        if (isset($info['data'])) {
            foreach ($info['data'] as $item) {
                $result[$item->getId()] = $item->getName();
            }
        }

        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);

        unset($info);

        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        return $data;
    }

    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getData(array $criteria)
    {
        $criteria['id'] = isset($criteria['id']) ? (int)$criteria['id'] : 0;

        $info = $this->get('core.common')->S($this->stag);

        if (empty($info)) {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);

            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if (is_numeric($criteria['id']))
            return isset($info[$criteria['id']]) ? $info[$criteria['id']] : "";


        if (is_array($criteria['id'])) {
            $result = array();

            foreach ($criteria['id'] as $item) {
                if (!isset($info[$item]))
                    continue;

                $result[$item] = $info[$item];
            }


            return $result;
        }
        return "";
    }

    public function getDataByName($name)
    {
        $info = $this->get('core.common')->S($this->stag);

        if (empty($info)) {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);

            $this->get('core.common')->S($this->stag, $info, 86400);
        }
        $info = array_flip($info);
        return isset($info[$name]) ? $info[$name] : "";
    }

    /**
     * 获取查询条件内所有城区
     *
     * @param $criteria
     * @param $order
     *
     * @return array ['id'=>'name']
     */
    public function getAreasInfo($criteria = [], $order = [])
    {
        $criteria += ['id_delete' => 0, 'checked' => 1];

        if (!$areas = $this->findBy($criteria, $order))
            return [];

        $info = [];

        foreach ($areas['data'] as $area) {
            $info[$area->getId()] = $area->getName();
        }

        return $info;
    }

    /**
     * 获取查询条件内所有城区
     *
     * @param $criteria
     * @param $order
     *
     * @return array ['id'=>Area]
     */
    public function getAreaItem($criteria = [], $order = [])
    {
        $criteria += ['id_delete' => 0, 'checked' => 1];

        if (!$areas = $this->findBy($criteria, $order))
            return [];

        $info = [];

        foreach ($areas['data'] as $area) {
            $area->setPolyline('');
            $info[$area->getId()] = $area;
        }

        return $info;
    }

    /**
     * 根据ID查询属性表中的名称
     * @param array $id ID
     * @return array $name 名称
     */
    public function findArea($id)
    {

        if ($id) {
            $findcate = $this->get('house.catemetro')->findOneBy(array('is_delete' => 0, 'id' => $id));
            if ($findcate) {
                //返回名称
                return $findcate->getName();
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}