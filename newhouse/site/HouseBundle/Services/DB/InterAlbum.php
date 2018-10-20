<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年09月27日
 */

namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 楼盘相册
 *
 */
class InterAlbum extends AbstractServiceManager
{
    protected $table = 'InterAlbum';

    /**
     * album 类型
     */
    const ALBUM_TYPES = [
        1 => '效果图',
        2 => '实景图',
        3 => '样板图',
        4 => '小区图',
        5 => '交通图',
        6 => '规划图',
        7 => '配套图',
        8 => '鸟瞰图',
        9 => '活动现场图'
    ];

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 获取查询条件内所有分类
     *
     * @param $criteria
     * @param $order
     *
     * @return array ['aid'=>[InterAlbum, InterAlbum, InterAlbum]]
     */
    public function getInterAlbumItem($criteria, $order = [])
    {
        $criteria += ['checked' => 1, 'is_delete' => 0];

        if (!$interAlbums = $this->findBy($criteria, $order))
            return [];

        $info = [];

        foreach ($interAlbums['data'] as $interAlbum) {
            if (isset($info[$interAlbum->getAid()])) {
                array_push($info[$interAlbum->getAid()], $interAlbum);
            } else {
                $info[$interAlbum->getAid()] = [$interAlbum];
            }
        }

        return $info;
    }
}