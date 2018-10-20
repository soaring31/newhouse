<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年05月27日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 栏目分类
 *
 */
class Category extends AbstractServiceManager
{
    const STATUS_RGB_COLOR = [
        '309' => '#FF8400',     // 在售
        '117' => '#FF5C36',     // 尾盘
        '114' => '#3F91F0',     // 待售
        '118' => '#AEAEAE',     // 售罄
        '115' => '#FF8400',     // 期房在售
        '116' => '#FF8400',     // 现房在售
    ];


    protected $table = 'Category';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 根据ID查询属性表中的名称
     * @param array $id ID
     * @return array $name 名称
     */
    public function findCateGory($id)
    {

        if ($id) {
            $findcate = $this->get('house.category')->findOneBy(array('is_delete' => 0, 'id' => $id));
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

    /**
     *
     *
     * @param array $enames
     * @return array ['id'=>'name']
     */
    public function findIdsByEname(array $enames)
    {
        $enames        = array_unique(array_filter($enames));
        $enameCriteria = [];

        foreach ($enames as $ename) {
            array_push($enameCriteria, ['ename' => $ename]);
        }


        $descs = $this->findBy([
            'name'      => [
                'orX' => $enameCriteria
            ],
            'checked'   => 1,
            'is_delete' => 0
        ]);

        if (!$descs)
            return [];

        $info = [];
        foreach ($descs['data'] as $desc) {
            $info[$desc->getId()] = $desc->getName();
        }

        return $info;
    }

    /**
     * 获取查询条件内所有分类
     *
     * @param $criteria
     * @param array $order
     *
     * @return array ['id'=>'name']
     */
    public function getCategoryInfo($criteria, $order = [])
    {
        if (!$categories = $this->findBy($criteria, $order))
            return [];

        $info = [];

        foreach ($categories['data'] as $category) {
            $info[$category->getId()] = $category->getName();
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
    public function getCategoryItem($criteria, $order = [])
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