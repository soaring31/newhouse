<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年06月24日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 商圈
 *
 */
class CateCircle extends AbstractServiceManager
{
    protected $table = 'CateCircle';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 获取查询条件内所有商圈信息
     *
     * @param $criteria
     *
     * @return array ['id'=>'name']
     */
    public function getCircleInfo($criteria = [])
    {
        $criteria = $criteria + ['id_delete' => 0, 'checked' => 1];

        if (!$circles = $this->findBy($criteria))
            return [];

        $info = [];

        foreach ($circles['data'] as $circle) {
            $info[$circle->getId()] = $circle->getName();
        }

        return $info;
    }

    /**
     * 获取查询条件内所有分类
     *
     * @param array $criteria
     * @param array $order
     *
     * @return array ['id'=>Category]
     */
    public function getCateCircleItem($criteria, $order = [])
    {
        $criteria += ['checked' => 1, 'is_delete' => 0];

        if (!$infos = $this->findBy($criteria, $order))
            return [];

        $items = [];

        foreach ($infos['data'] as $info) {
            $items[$info->getId()] = $info;
        }

        return $items;
    }
}