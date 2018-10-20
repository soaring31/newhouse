<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/26
 * Time: 12:24
 */

namespace HouseBundle\Handler;

use Doctrine\Common\Cache\MemcacheCache;
use HouseBundle\Entity\Area;
use HouseBundle\Entity\CateCircle;
use HouseBundle\Entity\CateLine;
use HouseBundle\Entity\EmptyEntity;
use HouseBundle\Entity\Houses;
use HouseBundle\Entity\HouseSemTel;
use HouseBundle\Form\Api\HouseSemType;
use HouseBundle\Services\DB\Around as AroundService;
use HouseBundle\Services\DB\Build as BuildService;
use HouseBundle\Services\DB\CateCircle as CateCircleService;
use HouseBundle\Services\DB\Category;
use HouseBundle\Services\DB\CateLine as CateLineService;
use HouseBundle\Services\DB\CateMetro;
use HouseBundle\Services\DB\Collects;
use HouseBundle\Services\DB\Collects as CollectsService;
use HouseBundle\Services\DB\Door as DoorService;
use HouseBundle\Services\DB\HousesArc as HousesArcService;
use HouseBundle\Services\DB\HousesAttr as HousesAttrService;
use HouseBundle\Services\DB\HousesPrice;
use HouseBundle\Services\DB\InterAlbum as InterAlbumService;
use HouseBundle\Services\DB\Prices;
use HouseBundle\Services\DB\Pushs as PushsService;
use HouseBundle\Services\DB\HouseSemTel as HouseSemTelService;
use HouseBundle\Services\DB\Video as VideoService;
use ManageBundle\Services\DB\AuthGroupArea;
use HouseBundle\Services\DB\Houses as HouseService;
use HouseBundle\Services\DB\HousesInfo;
use ManageBundle\Services\DB\News as NewsService;
use ManageBundle\Services\DB\Area as AreaService;
use ManageBundle\Services\DB\Users as UsersService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * house 通用查询
 *
 * Class HouseHandler
 * @package HouseBundle\Handler
 */
class HouseHandler extends HandlerBase
{
    /**
     * @var AuthGroupArea
     */
    private $authGroupAreaService;

    /**
     * @var Category
     */
    private $categoryService;

    /**
     * @var CateLineService
     */
    private $catelineService;

    /**
     * @var CateMetro
     */
    private $catemetroService;

    /**
     * 城市均价
     *
     * @var Prices
     */
    private $pricesService;

    /**
     * 楼盘价格
     *
     * @var HousesPrice
     */
    private $housePricesService;

    /**
     * @var HouseService
     */
    private $houseService;

    /**
     * @var HousesInfo
     */
    private $houseinfoService;

    /**
     * @var CateCircleService
     */
    private $cateCircleService;

    /**
     * @var HousesArcService
     */
    private $housesArcService;

    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * @var HousesAttrService
     */
    private $housesAttrService;

    /**
     * @var BuildService
     */
    private $buildService;

    private $doorService;

    private $aroundService;

    private $areaService;

    private $interAlbumService;

    private $pushsService;

    private $collectsService;

    private $usersService;

    private $videoService;

    /**
     * @var string
     */
    private $apiUrl;

    private $cache;

    private $container;

    //token 秘钥
    private $tokenkey = 'zhugefang2016';

    private $options;

    /**
     * @todo 计划把400 功能牵出去
     *
     * 400 终端
     */
    const TERMINALS = [
        'wap',
        'app',
        'pc'
    ];

    /**
     * 特色楼盘类型
     */
    const TSLPS = [
        1 => '低总价',
        2 => '小户型',
        3 => '品牌地产',
        4 => '现房',
        5 => '地铁沿线'
    ];

    public function __construct(Category $category,
                                CateLineService $cateLine,
                                CateMetro $cateMetro,
                                AuthGroupArea $authGroupArea,
                                Prices $prices,
                                HouseService $houses,
                                HousesInfo $housesInfo,
                                HousesPrice $housePricesService,
                                CateCircleService $cateCircle,
                                HousesArcService $housesArcService,
                                NewsService $newsService,
                                HousesAttrService $housesAttrService,
                                BuildService $buildService,
                                DoorService $door,
                                AroundService $around,
                                AreaService $areaService,
                                InterAlbumService $interAlbumService,
                                PushsService $pushsService,
                                CollectsService $collectsService,
                                UsersService $usersService,
                                VideoService $videoService,
                                MemcacheCache $cache,
                                $container)
    {
        $this->categoryService      = $category;
        $this->catelineService      = $cateLine;
        $this->catemetroService     = $cateMetro;
        $this->authGroupAreaService = $authGroupArea;
        $this->pricesService        = $prices;
        $this->houseService         = $houses;
        $this->houseinfoService     = $housesInfo;
        $this->housePricesService   = $housePricesService;
        $this->cateCircleService    = $cateCircle;
        $this->housesArcService     = $housesArcService;
        $this->newsService          = $newsService;
        $this->housesAttrService    = $housesAttrService;
        $this->buildService         = $buildService;
        $this->doorService          = $door;
        $this->aroundService        = $around;
        $this->areaService          = $areaService;
        $this->interAlbumService    = $interAlbumService;
        $this->pushsService         = $pushsService;
        $this->collectsService      = $collectsService;
        $this->usersService         = $usersService;
        $this->videoService         = $videoService;
        $this->cache                = $cache;
        $this->container            = $container;

        parent::__construct();
    }

    /**
     * 获取楼盘标签
     *
     * 获取规则 特色楼盘字段(tslp), 物业类型 cate_type, 装修程度 zxcd
     *
     * @param Houses $house
     * @return array [[id, ename, name], [id, ename, name], ...]
     */
    public function getTags(Houses $house, array $categories = [], $returnStauts = false)
    {
        if (!$categories) {
            $tagIds = [(int)$house->getCateStatus(), (int)$house->getCateType(), (int)$house->getZxcd()];

            if ($categories = $this->categoryService->findBy(['id' => ['in' => $tagIds]])) {
                $categories = $categories['data'];
            } else {
                return [];
            }
        }

        $tags   = [];
        $status = '';

        foreach ($categories as $key => $category) {
            if ($category->getEname() == 'cate_status') {
                $status = [
                    'id'    => $category->getId(),
                    'ename' => $category->getEname(),
                    'name'  => $category->getName(),
                    'rgb'   => $this->getValidData(Category::STATUS_RGB_COLOR, $category->getId())
                ];
            } else {
                array_push($tags, [
                    'id'    => $category->getId(),
                    'ename' => $category->getEname(),
                    'name'  => $category->getName(),
                ]);
            }

            unset($categories[$key]);
        }

        // 特色标签处理
        $tslps = $this->getUniqueArray(explode(',', $house->getTslp()));
        if ($tslps) {
            foreach ($tslps as $tslp) {
                array_push($tags, [
                    'id'    => $tslp,
                    'ename' => '',
                    'name'  => $this->getValidData(self::TSLPS, $tslp),
                ]);
            }
        }

        $returnStauts && $status && array_unshift($tags, $status);

        return $tags;
    }

    /**
     * 获取楼盘价格
     *
     * @param Houses $house
     *
     * @return string
     */
    public function getPrice(Houses $house)
    {
        $unit = '元/平';

        // 价格
        if ($highPrice = $house->getJgjj() && $lowPrice = $house->getJdjj()) {
            return sprintf('%s~%s %s', $lowPrice, $highPrice, $unit);
        }

        if ((int)$averagePrice = $house->getDj()) {
            return sprintf('%s %s', $averagePrice, $unit);
        }

        return '暂无';
    }

    /**
     * 手机号需要处理
     *
     * @param Houses $house
     * @return string
     */
    public function getTel(Request $request, Houses $house)
    {
        $platformType = $request->get('platformType');
        $tel          = ($platformType && ($platformType == 3 || $platformType == 4))
            ? ($house->getAppTel() ?: $house->getTel())
            : $house->getTel();
        $tel          = str_replace("转", ',', $tel);
        return $tel ?: '暂无';
    }

    /**
     * 全网比价
     *
     * @param Houses $house
     * @return array|mixed
     *
     * @throws \Exception
     */
    public function getGlobePrices(Houses $house)
    {
        $compareInfo = [];

        try {
            if (!$house->getComplexId())
                throw new NotFoundHttpException('楼盘比价信息不存在');

            $area = $house->getArea();
            if (!$authGroupArea = $this->authGroupAreaService->findOneBy(['rulesarea' => $area]))
                throw new NotFoundHttpException('楼盘地区不存在');

            if (!$city = $authGroupArea->getCity())
                throw new NotFoundHttpException('楼盘地区所在城市简称不存在');

            $url         = $this->apiUrl . "/API/NewHouseBorough/getComplexGov";
            $compareInfo = $this->httpUnit->post($url, [
                'city'       => $city,
                'complex_id' => $house->getComplexId()
            ]);

            $compareInfo = json_decode($compareInfo, true);

            if ($compareInfo['code'] == 200)
                $compareInfo = empty($compareInfo['data']['list']) ? [] : $compareInfo['data']['list'];

        } catch (\Exception $e) {

            // 记录错误日志

        }

        return $compareInfo;
    }

    /**
     * 获取动态资讯
     *
     * @param Houses $house
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getDynaInfo(Houses $house, $page = 1, $limit = 2000)
    {
        $criteria = [
            'checked'  => 1,
            'findType' => 1,
            'area'     => $house->getArea(),
            'aid'      => $house->getId(),
        ];

        // 动态资讯
        if ($houseArcs = $this->housesArcService->findBy([
                'cate_pushs' => 'lpdt',
                'models'     => 'news',
            ] + $criteria, ['id' => 'desc'], $limit, $page)) {
            $houseArcsIds = [];
            foreach ($houseArcs['data'] as $houseArc) {
                $houseArc && array_push($houseArcsIds, $houseArc['fromid']);
            }

            if ($houseArcsIds && $dynaInfos = $this->newsService->findBy([
                        'id' => ['in' => $houseArcsIds]
                    ] + $criteria)) {

                return $dynaInfos['data'];
            }
        }

        return [];
    }

    /**
     * 获取楼盘资讯
     *
     * @param Houses $house
     * @param int $page
     * @param int $limit
     * @param array $criteria 额外查询条件
     * @return array
     */
    public function getDoorInfo(Houses $house, $page = 1, $limit = 2000, $criteria = [])
    {
        $criteria = [
                'checked'  => 1,
                'findType' => 1,
                'area'     => $house->getArea(),
                'aid'      => $house->getId(),
            ] + $criteria;

        $info = [];

        // 户型
        if ($doors = $this->doorService->findBy($criteria, ['id' => 'desc'], $limit, $page)) {
            // 所有户型的销售状态
            $statusTexts = [];
            if ($statusIds = $this->doorService->getCateStatusIds()) {
                if ($categories = $this->categoryService->findBy(['id' => ['in' => $statusIds]])) {
                    foreach ($categories['data'] as $category) {
                        $statusTexts[$category->getId()] = $category->getName();
                    }
                }
            };

            // 所有的室厅卫厨
            $descInfos = $this->categoryService->findIdsByEname(['shi', 'ting', 'wei', 'chu']);

            foreach ($doors['data'] as &$v) {
                $v['cate_status_text'] = $this->getValidData($statusTexts, $v['cate_status']);
                $v['cate_status_rgb']  = $this->getValidData(Category::STATUS_RGB_COLOR, $v['cate_status']);
                $v['desc']             = sprintf('%s%s%s%s',
                    $v['shi'] ? $descInfos[$v['shi']] : '',
                    $v['ting'] ? $descInfos[$v['ting']] : '',
                    $v['wei'] ? $descInfos[$v['wei']] : '',
                    $v['chu'] ? $descInfos[$v['chu']] : '');
            }

            $info = $doors['data'];
        }

        return $info;
    }

    /**
     * 获取楼盘其他属性 house_attr
     *
     * @param Houses $houses
     * @return array|mixed
     *
     * @throws \Exception
     */
    public function getAttrInfo(Houses $houses)
    {
        $houseAttrs = $this->housesAttrService->findBy([
            'mid'      => $houses->getId(),
            'findType' => 1,
            'name'     => [
                'in' => [
                    'rjl',  // 容积率
                    'lhl',  // 绿化率
                    'content',  // 楼盘介绍
                    'zdmj', // 占地面积
                    'jzmj', // 建筑面积
                    'dfl',  // 得房率
                    'sldz', // 售楼地址
                    'xkzh', // 许可证号
                    'wyf',  // 物业费
                    'wydz', // 物业地址
                    'wygs', // 物业公司
                    'jtxl', // 交通线路
                    'xqss', // 学区所属
                    'cksm', // 停车位
                    'jfrq', // 交房日期
                    'qqqun', //qq群
                    'ltbk', // 业主论坛
                ]
            ]
        ]);

        if (!$houseAttrs)
            return [];

        $res = [];

        foreach ($houseAttrs['data'] as $v) {
            $res[$v['name']] = $v['value'];
        }

        return $res;
    }

    /**
     * 获取猜你喜欢楼盘
     *
     * @param array $criteria
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getInterestHouse(Houses $house, array $criteria = [])
    {
        // 接入传漾广告
        $area       = $house->getArea();
        $adIds      = $this->getChuanyangAdIds($area, ['item' => 'interest']);
        $adCriteria = ['id' => ['in' => $adIds]];
        if ($adIds) {
            $interestHouses = $this->houseService->findBy($adCriteria, ['id' => 'desc'], 15, 1);

            return $this->getHouseListInfo($interestHouses['data']);
        }

        if ($interests = $this->pushsService->findBy([
                'cate_pushs' => 'tuijian_list_cnxh',
                'models'     => 'houses'
            ] + $criteria)) {

            $interestsIds = [];
            foreach ($interests['data'] as $interest) {
                array_push($interestsIds, $interest->getFromId());
            }

            if ($interestsIds && $interestHouses = $this->houseService->findBy([
                        'id' => ['in' => $this->getUniqueArray($interestsIds)],
                    ] + $criteria)) {

                return $this->getHouseListInfo($interestHouses['data']);
            }
        }

        $interestHouses = $this->houseService->findBy($criteria, ['id' => 'desc'], 15, 1);

        return $this->getHouseListInfo($interestHouses['data']);
    }

    /**
     * 获取楼房视频
     *
     * @param Houses $house
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getVideo(Houses $house, $page = 1, $limit = 2000)
    {
        $criteria = [
            'checked'  => 1,
            'findType' => 1,
            'area'     => $house->getArea(),
            'aid'      => $house->getId(),
        ];

        // 视频信息
        if ($houseArcs = $this->housesArcService->findBy([
                'cate_pushs' => 'video',
                'models'     => 'video',
            ] + $criteria, ['id' => 'desc'], $limit, $page)) {
            $houseArcsIds = [];
            foreach ($houseArcs['data'] as $houseArc) {
                $houseArc && array_push($houseArcsIds, $houseArc['fromid']);
            }

            if ($houseArcsIds && $dynaInfos = $this->videoService->findBy([
                        'id' => ['in' => $houseArcsIds]
                    ] + $criteria)) {

                return $dynaInfos['data'];
            }
        }

        return [];
    }

    /**
     * 户型数据
     *
     * @param Houses $houses
     * @return array
     */
    public function getDoorArea(Houses $houses)
    {
        $criteria = [
            'checked' => 1,
            'area'    => $houses->getArea(),
            'aid'     => $houses->getId(),
        ];

        $big   = 0;
        $small = 0;

        $doors = $this->doorService->findBy($criteria);
        if (!$doors)
            return [];

        foreach ($doors['data'] as $door) {
            $big = ($door->getMj() > $big) ? $door->getMj() : $big;

            if (!$small)
                $small = $door->getMj();
            else
                $small = ($door->getMj() < $small) ? $door->getMj() : $small;
        }

        return [
            'big'   => $big,
            'small' => $small,
            'data'  => $doors['data']
        ];

    }

    /**
     * 楼栋数据
     *
     * @param Houses $houses
     * @return array
     */
    public function getBuilding(Houses $houses)
    {
        $criteria = [
            'checked' => 1,
            'area'    => $houses->getArea(),
            'aid'     => $houses->getId(),
        ];

        $builds = $this->buildService->findBy($criteria);
        if (!$builds)
            return [];

        $total            = count($builds['data']);
        $totalHouseKeeper = 0;

        foreach ($builds['data'] as &$build) {
            $totalHouseKeeper += $build->getHushu();
        }

        $door = $this->getDoorArea($houses);

        $text = sprintf('项目共%d栋楼，小区规划总户数%d户，户型面积为%d-%d平米。',
            $total, $totalHouseKeeper,
            $door['small'], $door['big']);

        return [
            'text' => $text,
            'data' => $builds['data']
        ];
    }

    /**
     * 获取所有分类
     *
     * @param $criteria
     *
     * @return array ['id'=>'name']
     */
    public function getCategory($criteria)
    {
        return $this->categoryService->getCategoryInfo($criteria);
    }

    /**
     * 获取所有商圈
     *
     * @param $criteria
     *
     * @return array ['id'=>'name']
     */
    public function getCircle($criteria = [])
    {
        return $this->cateCircleService->getCircleInfo($criteria);
    }

    /**
     * 获取所有城区
     *
     * @param $criteria
     *
     * @return array ['id'=>'name']
     */
    public function getAreas($criteria = [])
    {
        return $this->areaService->getAreasInfo($criteria);
    }

    /**
     * @param Houses $houses
     * @param array $areas
     * @param array $catecircles
     *
     * @return string
     */
    public function getAddressAreaInfo(Houses $houses, $areas = [], $catecircles = [])
    {
        $regionName     = '';
        $cateCircleName = '';

        if ($areas) {
            $regionName = $this->getValidData($areas, $houses->getRegion());
        }

        if ($catecircles) {
            $cateCircleName = $this->getValidData($catecircles, $houses->getCateCircle());
        }

        if (!$regionName && $region = $this->areaService->findOneBy([
                'id' => $houses->getRegion()
            ])) {

            $regionName = $region->getName();
        }

        if (!$catecircles && $cateCircle = $this->cateCircleService->findOneBy([
                'id' => $houses->getCateCircle()
            ])) {

            $cateCircleName = $cateCircle->getName();
        }

        return $this->getAddressPrefix($regionName, $cateCircleName);
    }

    /**
     * 获取地址前缀
     *
     * @param string $area
     * @param string $region
     *
     * @return string
     */
    public function getAddressPrefix($area = '', $region = '')
    {
        $prefix = '';

        switch (true) {
            case (!$area && !$region):
                break;
            case (!$area):
                $prefix = "[$region ]";
                break;
            case (!$region):
                $prefix = "[ $area]";
                break;
            default:
                $prefix = "[ $area-$region ]";
        }

        return $prefix;
    }

    /**
     * 获取周边配套信息
     *
     * 返回格式 ['学校'=>[], '医院'=>[] ... ]
     *
     * @param Houses $houses
     * @return array
     */
    public function getAround(Houses $houses, $isAndroid = false)
    {
        $criteria = [
            'checked' => 1,
            'area'    => $houses->getArea(),
            'aid'     => $houses->getId(),
        ];

        // 获取周边配套数据
        if (!$aroundInfo = $this->getCategory(['ename' => 'category', 'models' => 'around']))
            return [];

        // 转为 ['name'=>'id']
        $aroundInfo = array_flip($aroundInfo);

        if ($houseArcs = $this->housesArcService->findBy([
                'cate_pushs' => 'zhoubian',
                'models'     => 'around',
            ] + $criteria, ['id' => 'desc'], 2000, 1)) {
            $houseArcsIds = [];
            foreach ($houseArcs['data'] as $houseArc) {
                $houseArc && array_push($houseArcsIds, $houseArc->getFromId());
            }

            if ($houseArcsIds && $arounds = $this->aroundService->findBy([
                        'id' => ['in' => $houseArcsIds]
                    ] + $criteria)) {

                current($aroundInfo);

                foreach ($aroundInfo as $name => $id) {
                    $tmpId             = $id;
                    $aroundInfo[$name] = $isAndroid ? '' : [];

                    foreach ($arounds['data'] as &$around) {
                        if ($around->getCategory() == $tmpId) {

                            if ($isAndroid) {
                                $aroundInfo[$name] .= sprintf('<b>%s</b> %s', $around->getName(), $around->getAbstract());
                            } else {
                                array_push($aroundInfo[$name], sprintf('<b>%s</b> %s', $around->getName(), $around->getAbstract()));
                            }

                            unset($around);
                        }
                    }
                }

                return array_filter($aroundInfo);
            }
        }

        return [];
    }

    /**
     * @param array $houses
     *
     * @return array
     */
    public function perfectHouses(array $houses)
    {
        // 所有状态
        $statusIds = [];

        // 获取状态
        foreach ($houses as $house) {
            array_push($statusIds, $house['cate_status']);
        }

        $allStatus = $statusIds ? $this->getCategory(['id' => ['in' => $statusIds]]) : [];

        foreach ($houses as &$house) {
            $house['cate_status_text'] = $this->getValidData($allStatus, $house['cate_status']);
        }

        return $houses;
    }

    /**
     * 用户是否收藏此楼盘
     *
     * @param Houses $houses
     * @param int $userId
     * @param int $oldUserId
     *
     * @return bool
     */
    public function isCollect(Houses $houses, $userId = 0, $oldUserId = 0)
    {
        if (!$userId && !$oldUserId)
            return false;

        $criteria = [];
        $userId && $criteria['id'] = $userId;
        $oldUserId && $criteria['oid'] = $oldUserId;

        if ($user = $this->usersService->findOneBy($criteria)) {
            if ($collects = $this->collectsService->findBy(['aid' => $houses->getId(), 'uid' => $user->getId()])) {
                return count($collects['data']) > 0;
            }
        }

        return false;
    }

    /**
     * 获取传漾广告楼盘 id集合
     *
     * @param $areaId  城市ID
     * @return array
     */
    public function getChuanyangAdIds($areaId, $ops = ['item' => ''])
    {
        try {
            //查询对应的分站ID
            if (!$authGroupArea = $this->authGroupAreaService->findOneBy(['rulesarea' => $areaId]))
                throw new NotFoundHttpException('楼盘地区不存在');
            $chuanyangAreaId = $authGroupArea->getId() + 100;

            // 猜你喜欢 id集合
            $interIds = ['11105101', '11105102', '11105103', '11105104'];

            // 新房热搜 id集合
            $hotIds = ['11103109', '11103110', '11103111', '11103112', '11103113'];

            // 关键词搜索
            $keywordIds = ['11103114', '11103115', '11103116', '11103117'];

            // 默认楼盘列表
            $houseList = ['13102101'];

            // H5楼盘列表
            $h5HouseList = ['12102108'];

            //APP广告位 ID集合
            $appIds = ['11101101', '11101102', '11101103', '11101104', '11101105', '11101106', '11101107', '11101108',
                '11101109', '11102101', '11102102', '11102103', '11103102', '11103103', '11103107', '11103108',
                '11104101', '11104102', '11104103', '11104104'];

            switch ($ops['item']) {
                case 'interest':
                    $ids = array_map(function ($val) use ($chuanyangAreaId) {
                        return $chuanyangAreaId . $val;
                    }, $interIds);
                    break;
                case 'hotHouse':
                    $ids = array_map(function ($val) use ($chuanyangAreaId) {
                        return $chuanyangAreaId . $val;
                    }, $hotIds);
                    break;
                case 'keyword':
                    $ids = array_map(function ($val) use ($chuanyangAreaId) {
                        return $chuanyangAreaId . $val;
                    }, $keywordIds);
                    break;
                case 'h5':
                    $ids = array_map(function ($val) use ($chuanyangAreaId) {
                        return $chuanyangAreaId . $val;
                    }, $h5HouseList);
                    break;
                case 'APP':
                    $ids = array_map(function ($val) use ($chuanyangAreaId) {
                        return $chuanyangAreaId . $val;
                    }, $appIds);
                    break;
                default:
                    $ids = array_map(function ($val) use ($chuanyangAreaId) {
                        return $chuanyangAreaId . $val;
                    }, $houseList);
            }


            $url    = 'http://tf.zhuge.com/s?z=zgf&op=7&c=' . implode(',', array_unique(array_filter($ids)));
            $result = $this->httpUnit->get($url);

            $buildingIds = [];
            if ($result && $list = json_decode($result, true)) {
                foreach ($list as $item) {
                    if (!empty($item['data']['buildingID'])) {
                        array_push($buildingIds, $item['data']['buildingID']);
                    }
                }
            }

            return array_unique($buildingIds);

        } catch (\Exception $e) {

            $this->logger->error('传漾广告解析失败', [$areaId, $ops]);

            return [];
        }
    }

    // region 400 电话

    /**
     * 添加, 更新400电话
     *
     * @param Houses $houses
     *
     * @return Houses
     *
     * @throws \Exception
     */
    public function updateServiceTel(Houses $houses)
    {
        if (!$houses->getTel())
            return $houses;

        // 获取城市所在区域, 城市信息
        $authGroupArea = $this->authGroupAreaService->findOneBy([
            'rulesarea' => $houses->getArea()]);

        if (!$cityName = $authGroupArea->getCity()) {
            $this->logger->error('添加400电话 楼盘所属城市有误', ['houseId' => $houses->getId()]);
            return $houses;
        }
        $url  = 'http://csr.zhugefang.com/api/Ji_Ya_Communicate/addJiyaBind';
        $data = [
            'complex_id'   => $houses->getId(),
            'called'       => $houses->getTel(),
            'bind_type'    => 1,  // 自动绑定,
            'complex_name' => $houses->getName(),
            'cityarea_id'  => $houses->getRegion(),
            'city'         => $cityName
        ];

        // 生成数据
        foreach (self::TERMINALS as $v) {
            $data['from'] = $v;
            $this->logger->info(sprintf('添加400电话 请求端%s', $v), $data);
            $addRes = $this->httpUnit->post($url, $data);
            $this->logger->info('添加400电话 请求结果', [$addRes]);

            $addRes = json_decode($addRes, true);
            $info   = $this->process400Result($addRes);

            if (!empty($info['bigcodetel']) && !empty($info['extcode'])) {
                switch ($v) {
                    case 'wap':
                        $houses->setWebTel(sprintf('%s转%s', $info['bigcodetel'], $info['extcode']));
                        break;
                    case 'app':
                        $houses->setAppTel(sprintf('%s转%s', $info['bigcodetel'], $info['extcode']));
                        break;
                    case 'pc':
                        $houses->setPcTel(sprintf('%s转%s', $info['bigcodetel'], $info['extcode']));
                        break;
                }
            }

            sleep(1);
        }

        return $houses;
    }

    /**
     * @param Houses $houses
     *
     * @return Houses
     *
     * @throws \Exception
     */
    public function delServiceTel(Houses $houses)
    {
        if (!$houses->getTel())
            return $houses;

        // 获取城市所在区域, 城市信息
        $authGroupArea = $this->authGroupAreaService->findOneBy([
            'rulesarea' => $houses->getArea()]);

        if (!$cityName = $authGroupArea->getCity()) {
            $this->logger->error('添加400电话 楼盘所属城市有误', ['houseId' => $houses->getId()]);
            return $houses;
        }

        $url  = 'http://csr.zhugefang.com/api/Ji_Ya_Communicate/delWorkGroup';
        $data = [
            'complex_id'   => $houses->getId(),
            'called'       => $houses->getTel(),
            'bind_type'    => 1,  // 自动绑定,
            'complex_name' => $houses->getName(),
            'cityarea_id'  => $houses->getRegion(),
            'city'         => $cityName
        ];

        // 删除数据
        foreach (self::TERMINALS as $v) {
            $data['from'] = $v;
            $this->logger->info(sprintf('删除400电话 请求端%s', $v), $data);
            $delRes = $this->httpUnit->post($url, $data);
            $this->logger->info('删除400电话 请求结果', [$delRes]);

            $delRes = json_decode($delRes, true);
            $info   = $this->process400Result($delRes);

            switch ($v) {
                case 'wap':
                    $houses->setWebTel('');
                    break;
                case 'app':
                    $houses->setAppTel('');
                    break;
                case 'pc':
                    $houses->setPcTel('');
                    break;
            }
        }

        return $houses;
    }

    /**
     * @param array $res
     *
     * @return mixed
     */
    private function process400Result(array $res)
    {
        if (!$res['data']['code'] == 200)
            $this->logger->warning('400电话 接口接入失败', $res);

        return $res['data'];
    }

    // endregion

    /**
     * 添加, 更新400电话
     *
     * @param Houses $houses
     *
     * @return Houses
     *
     * @throws \Exception
     */
    public function newUpdateSemTel(Houses $houses)
    {
        if (!$houses->getTel())
            return $houses;

        // 获取城市所在区域, 城市信息
        $authGroupArea = $this->authGroupAreaService->findOneBy([
            'rulesarea' => $houses->getArea()]);

        if (!$cityName = $authGroupArea->getCity()) {
            $this->logger->error('添加400电话 楼盘所属城市有误', ['houseId' => $houses->getId()]);
            return $houses;
        }
        $url  = 'http://csr.zhugefang.com/api/Ji_Ya_Communicate/addJiyaBind';
        $data = [
            'complex_id'   => $houses->getId(),
            'called'       => $houses->getTel(),
            'bind_type'    => 1,  // 自动绑定,
            'complex_name' => $houses->getName(),
            'cityarea_id'  => $houses->getRegion(),
            'city'         => $cityName
        ];

        // 生成数据
        foreach (self::TERMINALS as $v) {
            $data['from'] = $v;
            $this->logger->info(sprintf('添加400电话 请求端%s', $v), $data);
            $addRes = $this->httpUnit->post($url, $data);
            $this->logger->info('添加400电话 请求结果', [$addRes]);

            $addRes = json_decode($addRes, true);
            $info   = $this->process400Result($addRes);

            if (!empty($info['bigcodetel']) && !empty($info['extcode'])) {
                switch ($v) {
                    case 'wap':
                        $houses->setWebTel(sprintf('%s转%s', $info['bigcodetel'], $info['extcode']));
                        break;
                    case 'app':
                        $houses->setAppTel(sprintf('%s转%s', $info['bigcodetel'], $info['extcode']));
                        break;
                    case 'pc':
                        $houses->setPcTel(sprintf('%s转%s', $info['bigcodetel'], $info['extcode']));
                        break;
                }
            }

            sleep(1);
        }

        return $houses;
    }


    // endregion

    // region PriceTrends 价格走势

    /**
     * 获取最近 n个月(楼盘所属地区, 楼盘最热商圈(第一个))价格走势
     *
     * @param Houses $house
     * @param int $months
     *
     * @return array
     */
    public function getPriceTrends(Houses $house, $months = 6)
    {
        // 默认取最近6个月 该地区area均价走势
        $beginAt = date('Y-m-01', strtotime(sprintf('-%d month', $months)));
        $endAt   = date('Y-m-d', strtotime(sprintf("%s -1 day", date('Y-m-01'))));

        $areaPriceTrends = $circlePriceTrends = $housePriceTrends = ['name' => '', 'data' => ''];

        if ($area = $this->areaService->findOneBy(['id' => $house->getArea()])) {
            $areaPriceTrends = [
                'name' => $area->getName(),
                'data' => $this->getAreaPriceTrends($area, $beginAt, $endAt),
            ];
        }

        // 获取最近6个月 该楼盘所属商圈中 最热商圈中的第一个
        if ($cateCircle = current($this->houseService->getHotCircle($house->getArea(), 1))) {
            if ($cateCircle = $this->cateCircleService->findOneBy([
                'id' => $cateCircle['c_id']
            ])) {
                $circlePriceTrends = [
                    'name' => $cateCircle->getName(),
                    'data' => $this->getHotCirclePriceTrends($cateCircle, $beginAt, $endAt),
                ];
            }
        }

        // 获取最近6个月 该楼盘
        $housePriceTrends = [
            'name' => $house->getName(),
            'data' => $this->getHousePriceTrends($house, $beginAt, $endAt),
        ];

        return [
            'area'   => $areaPriceTrends,
            'circle' => $circlePriceTrends,
            'house'  => $housePriceTrends,
        ];
    }


    /**
     * 获取楼盘均价走势(按照开始, 结束时间) 按月返回
     *
     * @param Houses $houses
     *
     * @param string $beginAt
     *
     * @param string $endAt
     *
     * @return array
     */
    public function getHousePriceTrends(Houses $houses, $beginAt, $endAt)
    {
        $beginAt = new \DateTime($beginAt);
        $endAt   = new \DateTime($endAt);

        // 获取月份内所有housePrice数据 housePriceService
        $housePriceLists = $this->housePricesService->findBy([
            'aid'   => $houses->getId(),
            'jtime' => [
                'between' => [
                    sprintf("'%s'", $beginAt->format('Y-m-01')),
                    sprintf("'%s'", $endAt->format('Y-m-30')),
                ]
            ]
        ], ['jtime' => 'asc']);
        $houseInfo       = [];

        for ($m = $beginAt; $m <= $endAt; $beginAt->modify('+1 month')) {
            // 第一个月
            if ($housePriceLists) {
                $houseInfo[$m->format('Y-m-d')] = $this->getAverageHousePrice($m, $housePriceLists['data']);
            } else {
                $houseInfo[$m->format('Y-m-d')] = '';
            }
        }

        return $houseInfo;
    }

    /**
     * 获取热门商圈均价走势(按照开始, 结束时间) 按月返回
     *
     * 楼盘, 城区, 商圈
     *
     * @param CateCircle $cateCircle
     *
     * @param string $beginAt
     *
     * @param string $endAt
     *
     * @return array
     */
    public function getHotCirclePriceTrends(CateCircle $cateCircle, $beginAt, $endAt)
    {
        $beginAt = new \DateTime($beginAt);
        $endAt   = new \DateTime($endAt);

        // 通过热门商圈获取商圈内楼盘
        $houses = $this->houseService->findBy([
            'cate_circle' => $cateCircle->getId(),
            'area'        => $cateCircle->getArea(),
            'checked'     => 1,
            'findType'    => 1,
        ]);

        $housesIds = [];

        if (!$houses)
            return [];

        foreach ($houses['data'] as $house) {
            array_push($housesIds, $house['id']);
        }

        // 获取月份内所有housePrice数据 housePriceService
        $housePriceLists = $this->housePricesService->findBy([
            'aid'   => ['in' => $housesIds],
            'jtime' => [
                'between' => [
                    sprintf("'%s'", $beginAt->format('Y-m-01')),
                    sprintf("'%s'", $endAt->format('Y-m-30')),
                ]
            ]
        ], ['jtime' => 'asc']);
        $houseInfo       = [];

        for ($m = $beginAt; $m <= $endAt; $beginAt->modify('+1 month')) {
            // 第一个月
            if ($housePriceLists) {
                $houseInfo[$m->format('Y-m-d')] = $this->getAverageHousePrice($m, $housePriceLists['data']);
            } else {
                $houseInfo[$m->format('Y-m-d')] = '';
            }
        }

        return $houseInfo;
    }

    /**
     * 获取城区均价走势(按照开始, 结束时间) 按月返回
     *
     * 楼盘, 城区, 商圈
     *
     * @param Area $area
     *
     * @param string $beginAt
     *
     * @param string $endAt
     *
     * @return array
     */
    public function getAreaPriceTrends(Area $area, $beginAt = '', $endAt = '')
    {
        $beginAt = new \DateTime($beginAt);
        $endAt   = new \DateTime($endAt);

        // 通过该区域 price表内的所有数据
        $areaPrices = $this->pricesService->findBy([
            'area'     => $area->getId(),
            'findType' => 1,
        ], ['months' => 'asc']);

        $houseInfo = [];

        for ($m = $beginAt; $m <= $endAt; $beginAt->modify('+1 month')) {
            // 第一个月
            if ($areaPrices) {
                $houseInfo[$m->format('Y-m-d')] = $this->getAverageHousePrice($m, $areaPrices['data'], true);
            } else {
                $houseInfo[$m->format('Y-m-d')] = '';
            }
        }

        return $houseInfo;
    }

    // endregion

    // region Private 私有方法

    /**
     * @param mixed $apiUrl
     * @return $this
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * 获取一个时间月初, 月末内, 房价(cms_house_price, cms_price)数据的平均值
     *
     * @param \DateTime $date
     * @param array $housePriceData
     * @param boolean $isArea 是否是 cms_price 表
     *
     * @return float|int|string
     */
    private function getAverageHousePrice(\DateTime $date, array &$housePriceData, $isArea = false)
    {
        // 没有均价数据
        if (!$housePriceData)
            return '';

        $info = [];
        foreach ($housePriceData as &$v) {

            $newDate  = clone $date;
            $beginDay = $newDate->format('Y-m-01');
            $endDay   = date('Y-m-d', strtotime(sprintf("%s +1 month -1 day", $newDate->format('Y-m-01'))));

            if ($isArea) {
                $checkDay = $v['months']->format('Y-m-d');
            } else {
                $checkDay = $v->getJtime()->format('Y-m-d');
            }

            if ($checkDay >= $beginDay && $checkDay <= $endDay) {

                if ($isArea) {
                    array_push($info, $v['price']);
                } else {
                    array_push($info, $v->getDj());
                }

                unset($v);
            }
        }

        return array_sum($info) / (count($info) != 0 ? count($info) : 1);
    }

    /**
     * 获取有用的数据
     *
     * @param array|string $a
     * @param $key
     * @param  boolean $isObj 返回类型是否是对象
     *
     * @return mixed|string
     */
    public function getValidData($a, $key = '', $isObj = false)
    {
        if (is_string($a) && $a)
            return $a;

        if (!empty($a[$key]))
            return $a[$key];

        if ($isObj)
            return new EmptyEntity();

        return '';
    }

    /**
     * 清楚数据中的无用值以及重复数据
     *
     * @param array $info
     * @return array
     */
    public function getUniqueArray(array $info)
    {
        return array_unique(array_filter($info));
    }

    // endregion

    // region 新数据模型

    /**
     * 根据楼盘返回楼盘数据
     *
     * 包含 商圈信息, 地区信息, 图片信息(4张), 楼盘状态信息
     *
     * @param array $houses
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getHouseListInfo(array $houses, array $ops = [])
    {
        // 商圈 id 集合
        $cateCircleIds      = [];
        $cateCircleCriteria = !empty($ops['cateCircle']['criteria']) ? $ops['cateCircle']['criteria'] : [];     // 商圈额外查询条件
        // 地区 id 集合
        $regionIds      = [];
        $regionCriteria = !empty($ops['region']['criteria']) ? $ops['region']['criteria'] : []; // 地区额外查询条件
        // 图片 id 集合
        $houseIds        = [];
        $pictureCriteria = !empty($ops['interAlbum']['criteria']) ? $ops['interAlbum']['criteria'] : []; // 图片额外查询条件
        // 楼盘状态 id 集合
        $cateStatusIds  = [];
        $statusCriteria = !empty($ops['cateStatus']['criteria']) ? $ops['status']['criteria'] : []; // 楼盘状态额外查询条件

        foreach ($houses as &$house) {
            array_push($cateCircleIds, $house->getCateCircle());
            array_push($regionIds, $house->getRegion());
            array_push($regionIds, $house->getArea());
            array_push($houseIds, $house->getId());
            array_push($cateStatusIds, $house->getCateStatus());
        }

        // 获取商圈集合
        $cateCircles   = [];
        $cateCircleIds = $this->getUniqueArray($cateCircleIds);
        if ($cateCircleIds) {
            $cateCircles = $this->cateCircleService->getCateCircleItem(
                ['id' => ['in' => $cateCircleIds]] + $cateCircleCriteria);
        }

        // 获取地区集合
        $regions   = [];
        $regionIds = $this->getUniqueArray($regionIds);
        if ($regionIds) {
            $regions = $this->areaService->getAreaItem(['id' => ['in' => $regionIds]] + $regionCriteria,
                ['id' => 'desc']);
        }

        // 获取图片集合
        $pictures = [];
        if ($houseIds) {
            $pictures = $this->interAlbumService->getInterAlbumItem([
                    'aid' => ['in' => $houseIds]] + $pictureCriteria,
                ['id' => 'desc']);
        }

        // 获取状态集合
        $cateStatus = [];
        if ($cateStatusIds) {
            $cateStatus = $this->categoryService->getCategoryItem([
                    'id' => ['in' => $cateStatusIds]] + $statusCriteria,
                ['id' => 'desc']);
        }

        $returnInfo = [];

        foreach ($houses as &$house) {

            if (!$house instanceof Houses)
                throw new \Exception('实体无效');

            // 商圈信息
            $cateCircle       = $this->cateCircleService->findOneBy(['id' => $house->getCateCircle(), 'id_delete' => 0]);
            $parentCateCircle = new EmptyEntity();
            if ($cateCircle) {
                $parentCateCircle = $this->cateCircleService->findOneBy(['id' => $cateCircle->getPid(), 'id_delete' => 0]);
            }

            $albumnumItems = $this->getValidData($pictures, $house->getId());
            $status        = $this->getValidData($cateStatus, $house->getCateStatus());

            $info = [
                'id'                   => $house->getId(),
                'name'                 => $house ? $house->getName() : '',
                'cateStatus'           => $house->getCateStatus(),
                'dj'                   => $house->getDj() == 0 ? '待定' : $house->getDj(),
                'address'              => $this->getAddressAreaInfo($house) ." ". $house->getAddress(),
                'area'                 => $house->getArea(),
                'cateCircle'           => $house->getCateCircle(),
                'browseTime'           => $house->getUpdateTime(),
                // 新增字段
                'cateStatusItem'       => $this->getValidData($cateStatus, $house->getCateStatus(), true),
                'cateCircleItem'       => $this->getValidData($cateCircles, $house->getCateCircle(), true),
                'cateCircleParentItem' => $parentCateCircle,
                'albumnumItems'        => is_array($albumnumItems) ? array_slice($albumnumItems, 0, 4) : [],
                'regionItem'           => $this->getValidData($regions, $house->getRegion(), true),
                'areaItem'             => $this->getValidData($regions, $house->getArea(), true),
                'thumb'                => $this->getThumb($house),
                'price'                => $this->getPrice($house),
                'tag'                  => (array)$this->getTags($house, []),   // 标签, 销售状态, 装修程度, 物业类型, 特色楼盘
                'status'               => [
                    'id'   => $house->getCateStatus(),
                    'name' => $status ? $status->getName() : '',
                    'rgb'  => $this->getValidData(Category::STATUS_RGB_COLOR, $house->getCateStatus())
                ]
            ];

            array_push($returnInfo, $info);
        }

        return $returnInfo;
    }

    /**
     * @param Houses $houses
     *
     * @return string
     */
    public function getThumb(Houses $houses)
    {
        $env   = $this->container->get('kernel')->getEnvironment();
        $thumb = $houses->getThumb();

        if (substr($thumb, 0, 4) == 'http')
            return $thumb;

        $http = $env == 'dev' ? 'http://cms.zhuge.com/' : 'http://newhouse.zhuge.com/';

        return $http . $thumb;
    }

    /**
     * 神策埋点
     *
     * @param $data 数据
     * @param $type 埋点事件
     * @return array
     */
    public function getBurialPoint($data, $type)
    {
        try {
            //解密token
            $deToken             = $this->tokenDecode($data['token']);
            $point               = [];
            $point['event_name'] = $type;
            $point['project'] = 'cms';
            $point['role_type'] = (string)3;

            //SEM推广字段
            $point['spread'] = (string)$data['spread'];
            $point['kwd']    = (string)$data['kwd'];
            $point['pid']    = (string)$data['pid'];
            $point['unitid'] = (string)$data['unitid'];
            $point['refe']   = (string)$data['refe'];
            //组装参数
            if ($type == 'browse_newlist_page') {
                //查询物业类型
                $findCateType = '';
                if ($data['cate_type']) {
                    $findCateType = $this->categoryService->findCateGory($data['cate_type']);
                }
                //查询户型
                $finRoom = '';
                if ($data['room']) {
                    $finRoom = $this->categoryService->findCateGory($data['room']);
                }
                //查询地铁线
                $findCateLine = '';
                if ($data['cate_line']) {
                    $findCateLine = $this->catelineService->findCateLine($data['cate_line']);
                }
                //查询地铁站
                $findCateMetro = '';
                if ($data['cate_metro']) {
                    $findCateMetro = $this->catemetroService->findCateMetro($data['cate_metro']);
                }
                //查询城区
                $findRegion = '';
                if ($data['region']) {
                    $findRegion = $this->areaService->findArea($data['region']);
                }

                $point['area']             = (string)$findRegion;
                $point['deviceId']         = (string)$data['device_only_id'];
                $point['uid']              = (string)$deToken['id'];
                $point['propertyType']     = (string)$findCateType;
                $point['pagestart']        = (string)$data['page'];
                $point['pagelimit']        = (string)$data['limit'];
                $point['subway']           = (string)$findCateMetro;
                $point['subwayline']       = (string)$findCateLine;
                $point['cityarea_id']      = $data['region'] ? (string)$data['region'] : '';
                $point['appName']          = (string)$data['appName'];
                $point['platform']         = (string)$data['platformType'];
                $point['buildingType']     = '';
                $point['room']             = (string)$finRoom;
                $point['cityarea2_id']     = $data['cate_circle'] ? (string)$data['cate_circle'] : '';
                $point['city']             = (string)$data['city'];
                $point['sort']             = (string)$data['order'];
                $point['price']            = (string)$data['price'];
                $point['renovation']       = '';
                $point['tag']              = $this->houseService->getTslp($data['tslp']);
                $point['complex_loopline'] = '';
                $point['create_time']      = time();
                $point['source']           = '';
                $point['word']             = (string)$data['keyword'];
                $point['sale_status']      = '';
                $point['pageEntrance']     = '';

            } elseif ($type == 'newhouseinfo') {

                //把标签组合成字符串
                $tags    = '';
                $catType = '';
                if ($data['houseInfo']['tags']) {
                    foreach ($data['houseInfo']['tags'] as $k => $v) {
                        $tags .= $v['name'] . ",";
                        if ($v['ename'] == 'cate_type') {
                            $catType = $v['name'];
                        }
                    }
                }
                //取城区和商圈
                $regionName = '';
                if ($data['house'] && $region = $this->areaService->findOneBy([
                        'id' => $data['house']->getRegion()
                    ])) {
                    $regionName = $region->getName();
                }
                $cateCircleName = '';
                if ($data['house'] && $cateCircle = $this->cateCircleService->findOneBy([
                        'id' => $data['house']->getCateCircle()
                    ])) {

                    $cateCircleName = $cateCircle->getName();
                }

                $options      = $this->options;
                $findCateLine = '';
                $kpdate       = '';
                $region       = '';
                $cateCircle   = '';
                if ($data['house']) {
                    //查询附属楼盘信息
                    $criteria = [
                        'checked'         => 1,
                        'area'            => $data['house']->getArea(),
                        'house_developer' => $data['house']->getHouseDeveloper(),
                    ];

                    $houseInfo = $this->houseinfoService->findOneBy($criteria);
                    //查询地铁线
                    $findCateLine = $this->catelineService->findCateLine($data['house']->getCateLine());
                    //建筑类型
                    $findBuildingType = $this->getValidData($options['building_type'], $houseInfo->getComplexBuildingType());
                    $findHxs          = $this->getValidData($options['hxs'], $data['house']->getHxs());
                    $kpdate           = $data['house']->getKpdate()->format('Y-m-d');
                    $region           = $data['house']->getRegion();
                    $cateCircle       = $data['house']->getCateCircle();
                }

                $point['building_type_list'] = $findBuildingType;
                $point['cityarea2_name']     = (string)$cateCircleName;
                $point['cityarea_id']        = (string)$region;
                $point['cityarea_name']      = (string)$regionName;
                $point['complex_address']    = (string)$data['houseInfo']['address'];
                $point['complex_id']         = (string)$data['aid'];
                $point['complex_loopline']   = (string)$findHxs;
                $point['complex_name']       = (string)$data['houseInfo']['name'];
                $point['complex_tag']        = (string)trim($tags, ',');
                $point['developer_offer']    = (string)$data['houseInfo']['price'];
                $point['deviceId']           = (string)$data['device_only_id'];
                $point['house_subway']       = (string)$findCateLine;
                $point['last_delivertime']   = $kpdate;
                $point['main_houstype']      = '';
                $point['page_entrance']      = '';
                $point['property_type']      = (string)$catType;
                $point['sale_status']        = (string)$data['houseInfo']['tags'][0]['name'];
                $point['source_num']         = '';
                $point['uid']                = (string)$deToken['id'];
                $point['username']           = (string)$deToken['username'];
                $point['house_room']         = '';
                $point['appName']            = (string)$data['appName'];
                $point['platform']           = (string)$data['platformType'];
                $point['house_totalarea']    = '';
                $point['cityarea2_id']       = (string)$cateCircle;
                $point['city']               = (string)$data['city'];
                $point['create_time']        = time();
                $point['source']             = '';

            } elseif ($type == "collect" || $type == "cancelCollect") {

                $point['deviceId']     = (string)$data['device_only_id'];
                $point['page_from']    = '';
                $point['uid']          = (string)$deToken['id'];
                $point['username']     = (string)$deToken['username'];
                $point['appName']      = (string)$data['appName'];
                $point['platform']     = (string)$data['platformType'];
                $point['city']         = (string)$data['city'];
                $point['house_type']  = '';
                $point['complex_id']   = (string)$data['aid'];
                $point['create_time']  = time();
                $point['pageEntrance'] = '';
                $point['page_type']    = '';

            } elseif ($type == 'newhouse_tel') {

                $point['deviceId']        = (string)$data['device_only_id'];
                $point['page_from']       = '';
                $point['phone']           = (string)$data['virtualPhone'];
                $point['source_id']       = '';
                $point['uid']             = (string)$deToken['id'];
                $point['username']        = (string)$deToken['username'];
                $point['appName']         = (string)$data['appName'];
                $point['platform']        = (string)$data['platformType'];
                $point['city']            = (string)$data['city'];
                $point['complex_id']      = (string)$data['aid'];
                $point['create_time']     = time();
                $point['broker_username'] = '';
                $point['source']          = '';
                $point['pageEntrance']    = '';
                $point['page_type']       = '';

            }

            //请求地址
            $url = 'http://api.zhugefang.com/API/Sensors/cmsSensorsMethod';
            // 记录日志
            $this->logger->info(sprintf('请求事件%s埋点API地址 %s', $type, $url), [$point]);
            //请求参数
            $parameter         = [];
            $parameter['data'] = json_encode($point);

            $result = $this->httpUnit->post($url, $parameter);

            //记录日志
            $this->logger->info("请求返回值", [$result]);

        } catch (\Exception $e) {
            //打印日志
            $this->logger->error('调用埋点接口失败', ['url' => $data, 'type' => $type, "error" => json_encode($e)]);
            return [];
        }
    }

    /**
     * token 解码
     * @param  string token
     * @return array 解码之后的数据
     */
    public function tokenDecode($data = '')
    {
        if (!$data)
            throw new NotFoundHttpException('token 不存在');

        $secretKey = $this->tokenkey;
        $data      = str_replace(array('-', '_'), array('+', '/'), trim($data));
        $data      = base64_decode($data);
        $td        = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
        $iv        = mb_substr($data, 0, 32, 'latin1');
        mcrypt_generic_init($td, $secretKey, $iv);
        $data = mb_substr($data, 32, mb_strlen($data, 'latin1'), 'latin1');
        $data = mdecrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $aesStr = trim($data);
        if (!$aesStr)
            throw new BadRequestHttpException('token 无效');

        $aesArr = explode('|', $aesStr);
        return [
            'username'   => $aesArr[0],
            'id'         => $aesArr[1],
            'createTime' => $aesArr[2],
        ];
    }

    // endregion

    public function setOptions($options)
    {
        $this->options = $options;
    }
}