<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/11
 * Time: 16:32
 */

namespace HouseBundle\Controller\Api\V1;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\EmptyEntity;
use HouseBundle\Entity\Houses;
use HouseBundle\Entity\HousesInfo;
use HouseBundle\Entity\InterAlbum;
use HouseBundle\Services\DB\InterAlbum as InterAlbumService;
use HouseBundle\Form\Api\HouseListQueryType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Validator\Constraints\NotBlank;

class HouseController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="张树振---获取楼盘列表信息")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\w+")
     * @Rest\QueryParam(name="order", description="排序 kpdate|desc,price|asc", requirements="\w+")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="15")
     *
     * @throws \Exception
     */
    public function getHousesAction(Request $request)
    {
        $returnList = ['data' => [], 'count_complex' => 0];
        $form       = $this->createQueryForm();
        $form->submit($request->query->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data  = $form->getData();
        $page  = $data['page'];
        $limit = $data['limit'];

        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy([
            "city"      => $data['city'],
            'is_delete' => 0,
            'checked'   => 1
        ]))
            return $this->createNotFoundException('城市信息不存在');

        $areaId = $authGroupArea->getRulesarea();
        $query  = [];

        // 传漾广告
        $adHouses = [];
        if ($adHouseIds = $this->getHouseHandler()->getChuanyangAdIds($areaId)) {
            $query['id'] = [
                'notIn' => $adHouseIds
            ];

            $ad['id'] = [
                'in' => $adHouseIds
            ];

            if ($adHouses = $this->getHouseService()->findBy($ad))
                $adHouses = $adHouses['data'];
        }

        if (!$houses = $this->getHouseService()->findBy([
                "area"        => $areaId,
                'cate_status' => 309
            ] + $query,
            $this->getOrderQuery($data['order']) + ['kpdate' => 'desc', 'top' => 'desc', 'sort' => 'desc'],
            $limit, $page))
            return $this->returnSuccess($returnList);

        $totalCount = $houses['pageCount'];

        $housesItems = array_merge($adHouses, $houses['data']);
        $returnInfo  = $this->getHouseHandler()->getHouseListInfo($housesItems);

        foreach ($returnInfo as &$info) {
            $info['url'] = $this->getHouseDetailShareUrl($info);
        }

        return $this->returnSuccess([
            'data'          => $returnInfo,
            'count_complex' => $totalCount
        ], $page);
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘列表",
     *     input="HouseBundle\Form\Api\HouseListQueryType",
     *     views={"app"})
     *
     * @throws \Exception
     */
    public function getHouseListAction(Request $request)
    {
        try {
            //设置memcache缓存前缀
            $memcacheKey = 'house_list' . md5(json_encode($request->query->all()));
            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list);

            $returnList = ['data' => [], 'count_complex' => 0];

            $form = $this->createForm(new HouseListQueryType(), [
                'area' => 1,
                'city' => 'bj',
                'keyword' => '',
                'region' => 1,
                'cate_circle' => '',
                'cate_metro' => '',
                'price' => '',
                'room' => '',
                'tslp' => '',
                'cate_type' => '',
                'cate_line' => '',
                'tag' => '',
                'order' => '',
                'page' => 1,
                'limit' => 15
            ], [
                'method' => 'GET',
                'csrf_protection' => false,
                'allow_extra_fields' => true
            ])
                ->add('token', 'text', array('label' => 'token'))
                ->add('platformType', 'text', array('label' => '应用类型'))
                ->add('appName', 'text', array('label' => '应用名称'))
                ->add('device_only_id', 'text', array('label' => '设备ID'))
                ->add('spread', 'text', array('label' => '客户来源渠道'))
                ->add('kwd', 'text', array('label' => 'sem关键词id'))
                ->add('pid', 'text', array('label' => 'sem计划id'))
                ->add('unitid', 'text', array('label' => 'sem单元id'))
                ->add('refe', 'text', array('label' => '搜索词'));

            $form->submit($request->query->all());
            if ($form->isSubmitted() && !$form->isValid())
                throw new BadRequestHttpException('提交数据无效');

            $data = $form->getData();
            $query = ['checked' => 1];

            if (!$data['city'])
                return $this->returnSuccess($returnList);

            if (!$area = $this->getAuthGroupAreaService()->findOneBy(['city' => $data['city']]))
                return $this->returnSuccess($returnList);

            $query['area'] = $area->getRulesarea();

            // 传漾广告
            $adHouses = [];
            if ($adHouseIds = $this->getHouseHandler()->getChuanyangAdIds($area->getRulesarea())) {
                $query['id'] = [
                    'notIn' => $adHouseIds
                ];

                $ad['id'] = [
                    'in' => $adHouseIds
                ];

                if ($adHouses = $this->getHouseService()->findBy($ad))
                    $adHouses = $adHouses['data'];
            }

            if ($data['region']) {
                $query['region'] = $this->getQueryParams($data['region']);
            }

            if ($data['cate_circle']) {
                $query['cate_circle'] = $this->getQueryParams($data['cate_circle']);
            }

            if ($data['price']) {
                $queryPrices = explode('|', $data['price']);

                $item = current($queryPrices);
                $where = next($queryPrices);
                if (strtolower($item) == 'between') {
                    $criteria = $this->getHouseHandler()->getUniqueArray(explode(',', $where));
                } else {
                    $criteria = $where;
                };
                $query['dj'] = [$item => $criteria];
            }

            // 特色
            if ($data['tslp']) {
                $query['tslp'] = ['find' => $this->getQueryParams($data['tslp'])];
            }

            // 类型(物业类型)
            if ($data['cate_type']) {
                $query['cate_type'] = $this->getQueryParams($data['cate_type']);
            }

            // 标签(对应 house表内 tag字段)
            if ($data['tag']) {
                $query['tag'] = ['find' => $data['tag']];
            }

            // 地铁线
            if ($data['cate_line']) {
                $query['cate_line'] = ['find' => $data['cate_line']];
            }

            // 地铁站
            if ($data['cate_metro']) {
                $query['cate_metro'] = ['find' => $data['cate_metro']];
            }

            // 关键词搜索
            if ($data['keyword']) {
                $keyword = $data['keyword'];
                $query['name'] = ['like' => "%$keyword%"];
            }

            $query['cate_status'] = [
                'neq' => 118
            ];

            // 户型
            if ($data['room']) {
                if (!$doors = $this->getDoorsService()->findBy(['shi' => $data['room'], 'area' => $area->getRulesarea()]))
                    return $this->returnSuccess($returnList);

                $houseIds = [];
                foreach ($doors['data'] as $door) {
                    array_push($houseIds, $door->getAid());
                }

                $query['id'] = [
                    'in' => $this->getHouseHandler()->getUniqueArray($houseIds)
                ];
            }
            $result = $this->getHouseService()->findBy($query,
                $this->getOrderQuery($data['order']) + ['kpdate' => 'desc', 'top' => 'desc', 'sort' => 'desc'],
                $data['limit'], $data['page']);
            $totalCount = $result['pageCount'];

            //神策埋点
            $this->getHouseHandler()->getBurialPoint($data, 'browse_newlist_page');

            if (empty($result['data']))
                return $this->returnSuccess($returnList);

            $noDj = [];
            foreach ($result['data'] as $k => $datum) {
                if (!($datum->getDj()) || $datum->getDj() == 0) {
                    array_push($noDj, $datum);
                    unset($result['data'][$k]);
                }
            }

            $result['data'] = array_merge($adHouses, array_merge($result['data'], $noDj));

            $result['data'] = array_values($result['data']);//重置数组键

            //返回数据
            $listData = [
                'data' => $this->getHouseHandler()->getHouseListInfo($result['data']),
                'count_complex' => $totalCount
            ];
            //插入缓存
            $this->getMemcacheService()->save($memcacheKey, $listData, 3600*24);

            return $this->returnSuccess($listData);
        } catch (\Exception $e){
                        //打印日志
            $this->get('monolog.logger.zhuge')->error("查询详情页数据失败", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return $this->returnSuccess([]);
        }
    }

    /**
     * @ApiDoc(description="新开楼盘列表----王晓宇")
     *
     * 王晓宇
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+")
     * @Rest\QueryParam(name="date", description="日期（2018-04）" , requirements="\d+")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="10")
     *
     */
    public function postNewBuildAction(Request $request)
    {
        //定义数组
        $buildlist = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'city'  => 'bj',
            'date'  => '',
            'page'  => $this->page,
            'limit' => $this->limit,
        ], [
            'method'          => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->add('date', 'text')
            ->add('page', 'text')
            ->add('limit', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data  = $form->getData();//获取请求数据
        $page  = $data['page'];
        $limit = $data['limit'];
        //判断是否有日期
        if (!$data['date'])
            $data['date'] = date('Y-m', time());

        //获取当月的开始和结束
        $month = $this->getShiJianChuo(
            date('Y', strtotime($data['date'])),
            date('m', strtotime($data['date']))
        );
        $begin = date('Y-m-d', $month['begin']);
        $end   = date('Y-m-d', $month['end']);

        //获取上个月的开始和结束
        $timestamp = strtotime($begin);
        $lastBegin = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastEnd   = date('Y-m-d', strtotime("$lastBegin +1 month -1 day"));

        $criteria = [
            "city" => $data['city']
        ];
        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            throw new NotFoundHttpException('城市信息不存在');

        //查询当月新开楼盘的数据
        $findBuildLists = $this->getHouseService()
            ->getNewBuild(
                $begin,
                $end,
                $authGroupArea->getRulesarea(),
                $page,
                $limit
            );

        //统计当月新开楼盘的总数量
        $findBuildCount = $this->getHouseService()
            ->count([
                'area'   => $authGroupArea->getRulesarea(),
                'kpdate' => [
                    'between' => [sprintf("'%s'", $begin), sprintf("'%s'", $end)]
                ],
                'tag'    => 1
            ]);
        //查询上个月的新开楼盘的数据
        $findLastBuildLists = $this->getHouseService()
            ->getNewBuild(
                $lastBegin,
                $lastEnd,
                $authGroupArea->getRulesarea()
            );
        //判断上个月是否有数据
        if ($findLastBuildLists) {
            $lastMonth = 1;
        } else {
            $lastMonth = 0;
        }
        if (!$findBuildLists) {
            array_push($buildlist, [
                'lastMonth' => $lastMonth
            ]);
            return $this->returnSuccess($buildlist);//返回空数据
        }
        //获取数组中的元素
        $cateCircles = [];//定义商圈的数组
        $cateStatus  = [];//定义分类的数组
        $builds      = [];//定义楼栋的数组
        $region      = [];//定义城区的数组
        foreach ($findBuildLists as $key => $value) {
            if ($value['cate_circle'])
                array_push($cateCircles, $value['cate_circle']);
            if ($value['cate_status'])
                array_push($cateStatus, $value['cate_status']);
            if ($value['zxcd'])
                array_push($cateStatus, $value['zxcd']);
            if ($value['cate_type'])
                array_push($cateStatus, $value['cate_type']);
            if ($value['id'])
                array_push($builds, $value['id']);
            if ($value['region'])
                array_push($region, $value['region']);

        }

        //查询商圈的数据
        $circleWhere     = ['id' => ['in' => array_unique($cateCircles)], 'area' => $authGroupArea->getRulesarea()];
        $findCateCircles = $this->getCateCircleService()->findBy($circleWhere);
        //查询城区的数据
        $regionWhere = ['id' => ['in' => array_unique($region)]];
        $findRegion  = $this->getAreaService()->findBy($regionWhere);
        //查询分类的数据
        $typeWhere = ['id' => ['in' => array_unique($cateStatus)]];
        $findCates = $this->getCategoryService()->findBy($typeWhere);
        //查询楼栋的信息
        $buildWhere = [
            'aid'  => ['in' => array_unique($builds)],
            'kpsj' => [
                'between' => [sprintf("'%s'", $begin), sprintf("'%s'", $end)]
            ]
        ];
        $findBuilds = $this->getBuildsService()->findBy($buildWhere);
        $house_url  = $this->get('service_container')->getParameter('house_url');
        //统计数组
        $sum = '';
        foreach ($findBuildLists as $key => &$value) {
            $value['dj'] = $value['dj'] ? $value['dj'] . "/平起" : "待定";
            if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $value['thumb']) && $value['thumb'])
                $value['thumb'] = $house_url . "/" . $value['thumb'];

            $value['tslp'] = $this->getHouseService()->getTslp($value['tslp']);//特色楼盘
            //匹配商圈
            $value['cate_circle'] = '';
            if ($findCateCircles['data'])
                foreach ($findCateCircles['data'] as $k => $v) {
                    if ($value['cate_circle'] == $v->getId()) {
                        $value['cate_circle'] = $v->getName();
                    }
                }
            //匹配城区
            $value['cityproper'] = "";
            if ($findRegion['data'])
                foreach ($findRegion['data'] as $k => $v) {
                    if ($value['region'] == $v->getId()) {
                        $value['cityproper'] = $v->getName();
                    }
                }
            //匹配分类
            $value['cate_status'] = '';
            $value['cate_type']   = '';
            $value['zxcd']        = '';
            if ($findCates['data'])
                foreach ($findCates['data'] as $k => $v) {
                    if ($value['cate_status'] == $v->getId()) {
                        $value['cate_status'] = $v->getName();
                    }
                    if ($value['cate_type'] == $v->getId()) {
                        $value['cate_type'] = $v->getName();
                    }
                    if ($value['zxcd'] == $v->getId()) {
                        $value['zxcd'] = $v->getName();
                    }
                }
            //匹配楼栋
            $louDong = '';//楼栋字符串
            if ($findBuilds['data'])
                foreach ($findBuilds['data'] as $k => $v) {
                    if ($value['id'] == $v->getAid()) {
                        $louDong .= $v->getName() . "、";
                    }
                }
            //楼栋合并成字符串
            $value['builds'] = rtrim($louDong, '、');
            //把商圈组合成字符串
            if ($value['cate_circle'])
                $sum .= $value['cate_circle'] . ",";

            $value['address'] = "[ " . $value['cityproper'] . "-" . $value['cate_circle'] . " ]" . $value['address'];
            unset($value['tag']);
            unset($value['region']);
        }
        //置顶数据
        $setTop = [
            'name'      => ltrim(date('m', strtotime($data['date'])), 0) . "月新开楼盘",
            'describe'  => '网罗全网楼盘 最新动态',
            'img'       => $house_url . "/uploads/house/settopimg.jpg",
            "introduce" => ltrim(date('m', strtotime($begin)), 0) . "月" . ltrim(date('d', strtotime($begin)), 0) . "日-" . ltrim(date('d', strtotime($end)), 0) . "日，预计北京" . $findBuildCount . "个楼盘入市，项目主要集中在" . trim($sum, ',') . "等区域"
        ];

        array_push($buildlist, [
            'lastMonth' => $lastMonth,
            'setTop'    => $setTop,
            'newBuild'  => $findBuildLists//新开楼盘数据
        ]);

        return $this->returnSuccess($buildlist);//以json格式返回数据
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘(新房)详情页数据",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="user_id", description="新数据用户id")
     * @Rest\QueryParam(name="old_user_id", description="旧数据用户id")
     * @Rest\QueryParam(name="token", description="token")
     * @Rest\QueryParam(name="city", description="城市简称")
     *  ````````````````````````````````````````````````````````
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseInfoAction(Request $request, $houseId)
    {
        try {
            //设置memcache缓存前缀
            $memcacheKey = 'house_details' . md5(json_encode($request->query->all())) . $houseId;
            if ($list = $this->getMemcacheService()->fetch($memcacheKey)) {
                if ($list) {
                    return $this->returnSuccess($list);
                }
            }

            if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
                throw new NotFoundHttpException('楼盘信息不存在');

            if (!$house instanceof Houses)
                throw new \Exception('实体类型错误');

            $data['userId']    = $request->query->get('user_id', 0);
            $data['oldUserId'] = $request->query->get('old_user_id', 0);
            $data['token']     = $request->query->get('token', 0);
            $data['city']      = $request->query->get('city', 0);
            //神策埋点参数
            $data['platformType']   = $request->query->get('platformType', 0);
            $data['appName']        = $request->query->get('appName', 0);
            $data['device_only_id'] = $request->query->get('device_only_id', 0);
            $data['aid']            = $houseId;
            $data['spread']         = $request->query->get('spread', 0);
            $data['kwd']            = $request->query->get('kwd', 0);
            $data['pid']            = $request->query->get('pid', 0);
            $data['unitid']         = $request->query->get('unitid', 0);
            $data['refe']           = $request->query->get('refe', 0);

            if (!$data['city'] && !$data['token'])
                return $this->returnSuccess([]);
            //获取域名
            $houseUrl = $this->get('service_container')->getParameter('house_url');
            $cache    = md5(json_encode($data));//缓存键
            //判断是否有缓存
            if ($tempContent = $this->get('core.common')->S($cache)) {
                return $this->returnSuccess($tempContent);
            }

            //插入楼盘浏览记录--王晓宇
            $this->addBrowse($house, $data['token'], $data['city']);

            $dynaInfos = $this->getHouseHandler()->getDynaInfo($house);
            foreach ($dynaInfos as &$dynaInfo) {
                $dynaInfo['redirect_url'] = sprintf('%s/news/detail/%s.html', $houseUrl, $dynaInfo['id']);
                $dynaInfo['content']      = strip_tags(str_replace('&nbsp;', '', $dynaInfo['content']));
                if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $dynaInfo['thumb']) && $dynaInfo['thumb'])
                    $dynaInfo['thumb'] = $houseUrl . "/" . $dynaInfo['thumb'];
            }

            // 楼盘基础信息
            $houseInfo = [
                'pictures' => [], //效果图
                'pictures_android' => [], //效果图
                'name' => $house ? $house->getName() : '',   // 名称
                'aliasName' => '',    // 别名
                'tags' => $this->getHouseHandler()->getTags($house, [], true), // 标签
                'price' => $this->getHouseHandler()->getPrice($house), // 价格区间
                'address' => $this->getHouseHandler()->getAddressAreaInfo($house) ." ". $house->getAddress(), // 楼盘地址
                'kpdate' => $house->getKpdate()->format('Y-m-d') != '1970-01-01'
                    ? $house->getKpdate()->format('Y-m-d')
                    : '暂无', // 开盘时间
                'phone'             => $this->getHouseHandler()->getTel($request, $house),    // 咨询电话
                'phone_title'       => '',    // 电话下面的文字
                'promotions'        => [],    // 最新活动
                'dynaInfo'          => $dynaInfos,    // 动态资讯
                'houseDynaInfo'     => [],    // 楼盘动态
                'doors'             => $this->getHouseHandler()->getDoorInfo($house),    // 户型
                'sandTable'         => [],    // 沙盘 楼栋信息
                'globeComment'      => [],    // 全网点评
                'houseEvaluation'   => [],    // 楼盘测评
                'advisers'          => [],    // 楼盘顾问
                'map'               => explode(',', $house->getMap()),    // 楼盘地图信息
                'globePrices'       => [],    // 全网比价
//            'priceTrends'       => [
//                'area'   => ['name' => '', 'data' => []],
//                'circle' => ['name' => '', 'data' => []],
//            ],    // 均价走势
                'forecastInterests' => [],    //  猜你喜欢,
                'is_collect'        => $this->getHouseHandler()->isCollect($house, $data['userId'], $data['oldUserId']),     // 是否收藏
                'share_url'         => $this->getHouseDetailShareUrl($house),     // 分享地址
                'update_at'         => $house->getUpdateTime(),
                'city_name'         => $this->getHouseHandler()->getAddressAreaInfo($house)
            ];

            $criteria = [
                'checked'  => 1,
                'findType' => 1,
                'area'     => $house->getArea(),
                'aid'      => $houseId,
            ];

            // 效果图
            if ($pictures = $this->getInterAlbumService()->findBy($criteria, ['cate_album' => 'desc'], 2000, 1)) {
                $houseInfo['pictures'] = $pictures['data'];
                $androidPic            = [];

                foreach ($pictures['data'] as $album) {
                    //替换无域名的图片地址
                    $album['thumb'] = $album['thumb'];
                    if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $album['thumb']) && $album['thumb'])
                        $album['thumb'] = $houseUrl . "/" . $album['thumb'];

                    if (!empty(InterAlbumService::ALBUM_TYPES[$album['cate_album']])) {
                        $name = InterAlbumService::ALBUM_TYPES[$album['cate_album']];
                        $id   = $album['cate_album'];

                        if (isset($androidPic[$id])) {
                            $androidPic[$id]['num']++;
                            array_push($androidPic[$id]['pic'], $album['thumb']);
                        } else {
                            $androidPic[$id] = [
                                'name' => $name,
                                'type' => $album['cate_pushs'],
                                'num'  => 1,
                                'id'   => $id,
                                'pic'  => [
                                    $album['thumb']
                                ]
                            ];
                        }
                    }
                }

//            if ($videos = $this->getHouseHandler()->getVideo($house)) {
//                array_push($androidPic, [
//                    'name' => '视频',
//                    'type' => 'video',
//                    'num'  => count($videos),
//                    'id'   => '',
//                    'pic'  => $videos,
//                ]);
//            }

                $houseInfo['pictures_android'] = array_values($androidPic);
            }

            // 最新活动
            if ($promotions = $this->getPromotionService()->findBy($criteria, ['id' => 'desc'], 2000, 1)) {
                foreach ($promotions['data'] as $k => &$v) {
                    //删除过期的活动数据
                    if (strtotime($v['enddate']->format('Y-m-d')) < strtotime(date('Y-m-d', time()))) {
                        unset($promotions['data'][$k]);
                    }
                    $v['thumb'] = $v['thumb'];
                    if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $v['thumb']) && $v['thumb'])
                        $v['thumb'] = $houseUrl . "/" . $v['thumb'];
                    $v['content'] = $v['subname'];
                }
                $houseInfo['promotions'] = array_values($promotions['data']);
            }

            // 楼盘动态 限制三条
            if ($houseDynaInfo = $this->getLoupandongtaiService()->findBy($criteria, ['update_time' => 'desc'], 3)) {
                $houseInfo['houseDynaInfo'] = $houseDynaInfo['data'];
            }

            // 沙盒图片
            $sandTable = [
                'picture' => '',
                'builds'  => [],
            ];
            if ($house->getDzsp()) {
                $sandTable['picture'] = $house->getDzsp();
                if ($builds = $this->getBuildsService()->findBy($criteria))
                    $sandTable['builds'] = $builds['data'];
            }
            $houseInfo['sandTable'] = $sandTable;

            // 全网点评
            if ($globeComments = $this->getInterHousesCommentService()->findBy($criteria, ['create_time' => 'desc'], 3)) {
                $houseInfo['globeComment'] = $globeComments['data'];
            }

            // 楼盘顾问
            if ($house) {
                $sellData              = $this->interAdviser($data['city'], $house->getComplexId());
                $houseInfo['advisers'] = $sellData;
            }
//        if ($advisers = $this->getInterAdverseService()->findBy($criteria)) {
//            foreach ($advisers['data'] as $k=>&$v){
//                $v['thumb'] = $v['thumb'];
//                if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $v['thumb'] ) && $v['thumb'] )
//                    $v['thumb']  = $houseUrl."/".$v['thumb'] ;
//            }
//            $houseInfo['advisers'] = $advisers['data'];
//        }

            // 全网比价
            if ($globePrices = $this->getHouseHandler()->getGlobePrices($house)) {
                $houseInfo['globePrices'] = $globePrices;
            }

            // 价格走势
            //$houseInfo['priceTrends'] = $this->getHouseHandler()->getPriceTrends($house);

            // 获取猜你喜欢
            $houseInfo['forecastInterests'] = $this->getHouseHandler()->getInterestHouse($house);

            //神策埋点
            $data['houseInfo'] = $houseInfo;
            $data['house']     = $house;
            $this->getHouseHandler()->getBurialPoint($data, 'newhouseinfo');

            //插入缓存
            $this->getMemcacheService()->save($memcacheKey, $houseInfo, 3600 * 24);

            return $this->returnSuccess($houseInfo);

        } catch (\Exception $e) {
            //打印日志
            $this->get('monolog.logger.zhuge')->error("查询详情页数据失败", [
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return $this->returnSuccess([]);
        }
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘详情数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="user_id", description="新数据用户id")
     * @Rest\QueryParam(name="old_user_id", description="旧数据用户id")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseDetailAction(Request $request, $houseId)
    {

        $userId    = $request->query->get('user_id', 0);
        $oldUserId = $request->query->get('old_user_id', 0);

        $options = $this->get('service_container')->getParameter('options');

        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        $criteria = [
            'checked' => 1,
            'area'    => $house->getArea(),
            'aid'     => $houseId,
        ];

        $houseInfo = $this->getHouseInfoService()->findOneBy($criteria);

        // 获取 houseAttr
        $houseAttrs = $this->getHouseHandler()->getAttrInfo($house);

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        // 用到的类型
        $categoryIds = [
            $house->getCateType(),  // 物业类型
            $house->getZxcd(),  // 装修状况
            $house->getCateStatus(),  // 销售状态
        ];

        $categoryInfos = $this->getHouseHandler()->getCategory(['id' => ['in' => $categoryIds]]);

        // 价格信息
        $priceLogInfo        = [];
        $priceLogInfoAndroid = [];
        //暂时不暂时价格---王晓宇
//        if ($priceLogs = $this->getHousesPriceService()->findBy($criteria)) {
//            foreach ($priceLogs['data'] as $log) {
//                array_push($priceLogInfo, [
//                    $log->getJtime()->format('Y-m-d'),
//                    $log->getDj(),
//                    $log->getJdjj(),
//                    $log->getBdsm(),
//                ]);
//
//                array_push($priceLogInfoAndroid, [
//                    'record_time'    => $log->getJtime()->format('Y-m-d'),
//                    'avg'            => $log->getDj(),
//                    'floor_price'    => $log->getJdjj(),
//                    'price_describe' => $log->getBdsm(),
//                ]);
//            }
//        }

        $houseDetail = [
            // 基本信息
            'base'             => [
                $this->toAppData('价格', $this->getHouseHandler()->getPrice($house), 'price'),
                $this->toAppData('物业类型',
                    $this->getValidData($categoryInfos, $house->getCateType()),
                    'cate_type'),
                $this->toAppData('项目特色',
                    $this->getValidData($options['tslp'], $house->getTslp()),
                    'tslp'),
                $this->toAppData('建筑类别',
                    $houseInfo ? $this->getValidData($options['building_type'], $houseInfo->getComplexBuildingType()) : '暂无数据',
                    'complex_building_type'),
                $this->toAppData('装修状况',
                    $this->getValidData($categoryInfos, $house->getZxcd()),
                    'zxcd'),
                $this->toAppData('建筑类型',
                    $this->getValidData($options['architectural'],
                        $houseInfo ? $houseInfo->getArchitectural() : '暂无数据'),
                    'architectural'),
                $this->toAppData('产权年限',
                    $houseInfo ? $houseInfo->getPropertyYear() : '暂无数据', 'property_year'),
                $this->toAppData('环线',
                    $this->getValidData($options['hxs'], $house->getHxs()),
                    'hxs'),
                $this->toAppData('开发商', $house->getHouseDeveloper() ?: '', 'house_developer'),
                $this->toAppData('楼盘地址', $house->getAddress(), 'address'),
            ],
            // 销售信息
            'sale'             => [
                $this->toAppData('销售状态',
                    $this->getValidData($categoryInfos, $house->getCateStatus()),
                    'status'),
                $this->toAppData('开盘时间', $house->getKpdate()->format('Y-m-d'), 'kpdate'),
                $this->toAppData('交房时间',
                    $houseInfo ? $houseInfo->getFirstnewDelivertime()->format('Y-m-d') : '暂无数据',
                    'first_new_deliver_time'),
                $this->toAppData('售楼地址', $house->getSldz(), 'sldz'),
                $this->toAppData('咨询电话', $this->getHouseHandler()->getTel($request, $house), 'tel'),
//                $this->toAppData('售楼许可证', $house->getXkzh(), 'xkzh'),
            ],
            // 售楼许可证
            'xkzh'             => [
                ['create_at' => '',
                 'value'     => $house->getXkzh(),
                 'builds'    => ''
                ]
            ],
            // 周边设施
            'around'           => $this->getHouseHandler()->getAround($house),
            'around_android'   => $this->getHouseHandler()->getAround($house, true),
            // 小区规划
            'planning'         => [
                $this->toAppData('物业公司', $house->getWygs(), 'wygs'),
                $this->toAppData('物业费用', $house->getWyf(), 'wyf'),
                $this->toAppData('水电燃气', $houseInfo ? sprintf('%s%s%s',
                    $houseInfo->getProvideWater(),      // 水
                    $houseInfo->getProvideElectric(),   // 电
                    $houseInfo->getProvideGas()         // 气
                ) : '暂无数据', 'sdrq'),
                $this->toAppData('供暖方式', $houseInfo ? $houseInfo->getProvideHeating() : '暂无数据', 'provide_heating'),
                $this->toAppData('绿化率',
                    $this->getValidData($houseAttrs, 'lhl')
                    , 'lhl'),
                $this->toAppData('车位情况',
                    $houseInfo ? $this->getValidData($houseInfo->getParkingCount()) : '暂无数据'
                    , 'parking_count'),
                $this->toAppData('容积率',
                    $this->getValidData($houseAttrs, 'rjl'),
                    'rjl'),
                $this->toAppData('楼栋信息',
                    $this->getValidData($this->getHouseHandler()->getBuilding($house), 'text')
                    , 'building'),
            ],
            'priceLog'         => [
                'title' => ['记录时间', '均价', '起价', '价格描述'],
                'data'  => $priceLogInfo
            ],
            'priceLog_android' => $priceLogInfoAndroid,
            'content'          => $this->toAppData('项目介绍',
                $this->getValidData($houseAttrs, 'content'),
                'content'),
            'disclaimer'       => $this->toAppData('免责声明', '本站楼盘信息旨在为用户提供更多信息的无常服务，信息以政府部门登记备案为准，请谨慎核查。', 'disclaimer'),
            'is_collect'       => $this->getHouseHandler()->isCollect($house, $userId, $oldUserId),     // 是否收藏
            'share_url'        => $this->getHouseDetailShareUrl($house)     // 分享地址
        ];

        return $this->returnSuccess($houseDetail);
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘开发商详细数据(查看更多详情)",
     *     views={"app"})
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseDeveloperAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        if (!$house->getHouseDeveloper())
            return $this->returnSuccess([]);

        $criteria = [
            'checked'         => 1,
            'area'            => $house->getArea(),
            'house_developer' => $house->getHouseDeveloper(),
        ];

        if (!$houses = $this->getHouseService()->findBy($criteria))
            return $this->returnSuccess([]);

        $developer = $house->getHouseDeveloper();
        $current   = [
            'name'     => $house->getName(),
            'cateType' => $house->getCateType(),
            'price'    => $this->getHouseHandler()->getPrice($house),
            'address'  => $house->getAddress()
        ];

        // 物业类型
        $categoryIds = [$house->getCateType()];

        // 项目名称, 物业类型, 价格, 项目地址
        $info = [];

        foreach ($houses['data'] as $value) {

            if ($value->getId() != $house->getId())
                continue;

            array_push($categoryIds, $value->getCateType());
            array_push($info, [
                'name'     => $value->getName(),
                'cateType' => $value->getCateType(),
                'price'    => $this->getHouseHandler()->getPrice($value),
                'address'  => $value->getAddress()
            ]);
        }

        $categoryInfos       = $this->getHouseHandler()->getCategory(['id' => ['in' => $categoryIds]]);
        $current['cateType'] = $this->getValidData($categoryInfos, $current['cateType'], true);

        foreach ($info as &$item) {
            $item['cateType'] = $this->getValidData($categoryInfos, $item['cateType'], true);
        }

        $returnInfo = [
            'name'    => $developer,
            'current' => $current,
            'other'   => $info
        ];

        return $this->returnSuccess($returnInfo);
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘效果图数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="che")
     *
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseAlbumAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $criteria = [
            'checked' => 1,
            'area'    => $house->getArea(),
            'aid'     => $house->getId(),
        ];

        $albums =
            $this->getInterAlbumService()
                ->findBy($criteria, ['id' => 'desc'], 2000, 1);

        if (!$albums)
            return $this->returnSuccess([]);

        $info = [];

        foreach ($albums['data'] as $album) {
            if ($album instanceof InterAlbum) {
                if (!empty(InterAlbumService::ALBUM_TYPES[$album->getCateAlbum()])) {
                    $name = InterAlbumService::ALBUM_TYPES[$album->getCateAlbum()];
                    $id   = $album->getCateAlbum();

                    if (isset($info[$id])) {
                        array_push($info[$id]['items'], $album);
                    } else {
                        $info[$id] = [
                            'name'  => $name,
                            'id'    => $id,
                            'items' => [$album]
                        ];
                    }
                }
            }
        }

        if ($videos = $this->getHouseHandler()->getVideo($house)) {
            array_push($info, [
                'name'  => '视频',
                'id'    => '',
                'items' => $videos,
            ]);
        }

        return $this->returnSuccess(array_values($info));
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘活动数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="page", default="1", description="当前页数")
     * @Rest\QueryParam(name=" limit", default="15", description="每页条数")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHousePromotionsAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);

        $criteria = [
            'checked' => 1,
            'area'    => $house->getArea(),
            'aid'     => $house->getId(),
        ];

        return $this->returnSuccess($this->getPromotionService()->findBy($criteria, ['id' => 'desc'], $limit, $page));
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘咨询数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="page", default="1", description="当前页数")
     * @Rest\QueryParam(name=" limit", default="15", description="每页条数")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseDynaInfoAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);
        //获取域名
        $houseUrl = $this->get('service_container')->getParameter('house_url');

        //增加楼盘资讯详情页跳转地址------王晓宇
        $dynaInfos = $this->getHouseHandler()->getDynaInfo($house, $page, $limit);
        foreach ($dynaInfos as &$dynaInfo) {
            $dynaInfo['android_abstract'] = $dynaInfo['abstract'];
            $dynaInfo['redirect_url']     = sprintf('%s/news/detail/%s.html', $houseUrl, $dynaInfo['id']);
            $dynaInfo['content']          = strip_tags(str_replace('&nbsp;', '', $dynaInfo['content']));
            if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $dynaInfo['thumb']) && $dynaInfo['thumb'])
                $dynaInfo['thumb'] = $houseUrl . "/" . $dynaInfo['thumb'];
        }

        return $this->returnSuccess($dynaInfos);
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘动态数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="page", default="1", description="当前页数")
     * @Rest\QueryParam(name=" limit", default="15", description="每页条数")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseDongtaiAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);

        $criteria = [
            'checked'  => 1,
            'area'     => $house->getArea(),
            'aid'      => $house->getId(),
            'findType' => 1,
        ];

        return $this->returnSuccess($this->getLoupandongtaiService()->findBy($criteria, ['update_time' => 'desc'], $limit, $page));
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘户型搜索因子(查看更多详情)",
     *     views={"app"})
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     *
     */
    public function getHouseDoorCriteriaAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $doors = $this->getHouseHandler()->getDoorInfo($house);
        $info  = [
            'shi'    => [
                ['id' => 0, 'name' => '全部', 'counter' => 0]
            ],
            'status' => [
                ['id' => 0, 'name' => '不限']
            ]
        ];

        $cateItems = $this->getHouseHandler()->getCategory([
            'name' => [
                'orX' => [
                    ['ename' => 'cate_status'],
                    ['ename' => 'shi'],
                ]
            ]
        ]);

        foreach ($doors as $key => $door) {
            $status = $door['cate_status'];
            $shi    = $door['shi'];

            $info['shi'][0]['counter'] += 1;

            if (isset($info['shi'][$shi])) {
                $info['shi'][$shi]['counter'] += 1;
            } else {
                $info['shi'][$shi] = [
                    'id'      => $shi,
                    'name'    => $this->getValidData($cateItems, $shi),    // 几居室
                    'counter' => 1
                ];
            }

            if (isset($info['status'][$status])) {
//                $info['status'][$status]['counter'] += 1;
            } else {
                if ($this->getValidData($cateItems, $status)) {
                    $info['status'][$status] = [
                        'id'   => $status,
                        'name' => $this->getValidData($cateItems, $status),    // 状态
                    ];
                }
            }
        }

        $info['shi']    = array_values($info['shi']);
        $info['status'] = array_values($info['status']);

        return $this->returnSuccess($info);
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘户型数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="shi", description="几居室(id)")
     * @Rest\QueryParam(name="cate_status", description="户型销售状态")
     *
     * @Rest\QueryParam(name="page", default="1", description="当前页数")
     * @Rest\QueryParam(name=" limit", default="15", description="每页条数")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseDoorAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $page       = $request->query->get('page', 1);
        $limit      = $request->query->get('limit', 15);
        $shi        = $request->query->get('shi', '');
        $cateStatus = $request->query->get('cate_status', '');
        $criteria   = [];

        $shi && $criteria['shi'] = $shi;
        $cateStatus && $criteria['cate_status'] = $cateStatus;

        return $this->returnSuccess($this->getHouseHandler()
            ->getDoorInfo($house, $page, $limit, $criteria));
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘点评数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="page", default="1", description="当前页数")
     * @Rest\QueryParam(name=" limit", default="15", description="每页条数")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseCommentAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);

        $criteria = [
            'checked'  => 1,
            'area'     => $house->getArea(),
            'aid'      => $house->getId(),
            'findType' => 1,
        ];

        return $this->returnSuccess($this->getInterHousesCommentService()->findBy($criteria, ['create_time' => 'desc'], $limit, $page));
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘顾问数据(查看更多详情)",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="page", default="1", description="当前页数")
     * @Rest\QueryParam(name=" limit", default="15", description="每页条数")
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseAdverseAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);

//        //获取域名
//        $houseUrl = $this->get('service_container')->getParameter('house_url');
//
//        $criteria = [
//            'checked' => 1,
//            'area'    => $house->getArea(),
//            'aid'     => $house->getId(),
//        ];
//        $adverse = $this->getInterAdverseService()->findBy($criteria, [
//            'id' => 'desc'
//        ], $limit, $page);
//        if($adverse['data']){
//            foreach ($adverse['data'] as $album) {
//                //替换无域名的图片地址
//                if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $album->getThumb()) && $album->getThumb())
//                    $album->setThumb($houseUrl . "/" . $album->getThumb());
//            }
//        }
        $adverse = [];
        // 楼盘顾问
        if ($house) {
            $criteria      = ['rulesarea' => $house->getArea()];
            $authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria);
            //调用置业顾问老版的数据
            $adverse = $this->interAdviser($authGroupArea->getCity(), $house->getComplexId());
        }

        return $this->returnSuccess($adverse);
    }

    /**
     * @ApiDoc(description="张树振---获取楼盘全网比价数据(查看更多详情)",
     *     views={"app"})
     *
     * @param Request $request
     * @param $houseId
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function getHouseGlobePricesAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        return $this->returnSuccess($this->getHouseHandler()->getGlobePrices($house));
    }

    /**
     * @ApiDoc(description="张树振---是否收藏",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="old_user_id", description="旧用户 id", requirements="\d+", default="0")
     * @Rest\QueryParam(name="user_id", description="新用户 id", requirements="\d+", default="0")
     *
     * @throws \Exception
     */
    public function getHouseCollectAction(Request $request, $houseId)
    {
        if (!$house = $this->getHouseService()->findOneBy(['id' => $houseId]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house instanceof Houses)
            throw new \Exception('实体类型错误');

        $userId    = $request->query->get('user_id', 0);
        $oldUserId = $request->query->get('old_user_id', 0);

        $isCollect = $this->getHouseHandler()->isCollect($house, $userId, $oldUserId);

        return $this->returnSuccess([
            'status' => $isCollect > 0 ? 1 : 2
        ]);
    }

    /**
     * @ApiDoc(description="张树振---获取热门楼盘",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\w+")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="15")
     *
     * @throws \Exception
     */
    public function getHotHousesAction(Request $request)
    {
        $city  = $request->query->get('city', 'bj');
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);

        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy([
            "city"      => $city,
            'is_delete' => 0,
            'checked'   => 1
        ]))
            return $this->createNotFoundException('城市信息不存在');

        // 传漾广告
        $adIds    = $this->getHouseHandler()->getChuanyangAdIds($authGroupArea->getRulesarea(), ['item' => 'hotHouse']);
        $criteria = ["area"        => $authGroupArea->getRulesarea(),
                     'cate_status' => 309];
        $adIds && $criteria['id'] = ['in' => $adIds];

        $houses = $this->getHouseService()->findBy($criteria,
            $this->getOrderQuery('clicks|desc'),
            15, $page);

        if (!empty($houses['data']))
            return $this->returnSuccess($houses['data']);

        return $this->returnSuccess([]);
    }

    /**
     * @ApiDoc(description="张树振---获取传漾广告关键词楼盘",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\w+")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="15")
     *
     * @throws \Exception
     */
    public function getKeywordHousesAction(Request $request)
    {
        $city  = $request->query->get('city', 'bj');
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 15);

        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy([
            "city"      => $city,
            'is_delete' => 0,
            'checked'   => 1
        ]))
            return $this->createNotFoundException('城市信息不存在');

        // 传漾广告
        $adIds = $this->getHouseHandler()->getChuanyangAdIds($authGroupArea->getRulesarea(), ['item' => 'keyword']);
        if (!$adIds)
            return $this->returnSuccess([]);

        $criteria = ["area"        => $authGroupArea->getRulesarea(),
                     'cate_status' => 309, 'id' => ['in' => $adIds]];

        $houses = $this->getHouseService()->findBy($criteria,
            $this->getOrderQuery('clicks|desc'),
            $limit, $page);

        if (!empty($houses['data']))
            return $this->returnSuccess($houses['data']);

        return $this->returnSuccess([]);
    }

    /**
     * @ApiDoc(description="400拨打电话记录----王晓宇")
     *
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+")
     * @Rest\QueryParam(name="aid", description="楼盘ID" , requirements="\d+")
     * @Rest\QueryParam(name="uid", description="老用户ID" , requirements="\d+")
     * @Rest\QueryParam(name="platformType", description="设备类型 6： Wap 2：PC 3：IOS 4：Android " , requirements="\d+")
     * @Rest\QueryParam(name="virtualPhone", description="400 电话" , requirements="\d+")
     * @Rest\QueryParam(name="spread", description="客户来源渠道" , requirements="\d+")
     *
     */
    public function postPhoneRecordAction(Request $request)
    {
        try {
            //定义数组
            $buildlist = array();
            //定义请求参数默认值以及请求方式
            $form = $this->createFormBuilder([
                'city'           => '',
                'aid'            => '',
                'uid'            => '',
                'virtualPhone'   => '',
                'token'          => '',
                'platformType'   => '',
                'appName'        => '',
                'device_only_id' => '',
                'spread'         => '',
                'kwd'            => '',
                'pid'            => '',
                'unitid'         => '',
                'refe'           => '',
            ], [
                'method'          => 'POST',
                'csrf_protection' => false
            ])
                ->add('city', 'text', [
                    'constraints' => [
                        new NotBlank(['message' => '城市简称不能为空']),
                    ]
                ])
                ->add('aid', 'text', [
                    'constraints' => [
                        new NotBlank(['message' => '楼盘ID不能为空']),
                    ]
                ])
                ->add('virtualPhone', 'text', [
                    'constraints' => [
                        new NotBlank(['message' => '400电话不能为空']),
                    ]
                ])
                ->add('uid', 'text', array('label' => '用户ID'))
                ->add('token', 'text', array('label' => 'token'))
                ->add('platformType', 'text', array('label' => '应用类型'))
                ->add('appName', 'text', array('label' => '应用名称'))
                ->add('device_only_id', 'text', array('label' => '设备ID'))
                ->add('spread', 'text', array('label' => '客户来源渠道'))
                ->add('kwd', 'text', array('label' => 'sem关键词id'))
                ->add('pid', 'text', array('label' => 'sem计划id'))
                ->add('unitid', 'text', array('label' => 'sem单元id'))
                ->add('refe', 'text', array('label' => '搜索词'))
                ->getForm();

            $form->submit($request->request->all());//接收请求数据
            if (!$form->isValid())
                throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $data = $form->getData();//获取请求数据
            //神策埋点
            $this->getHouseHandler()->getBurialPoint($data, 'newhouse_tel');

            if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy([
                "city"      => $data['city'],
                'is_delete' => 0,
                'checked'   => 1
            ]))
                throw new NotFoundHttpException('城市信息不存在');

            //插入400拨打记录
            $phoneData = [
                'uid'           => '',
                'area'          => $authGroupArea->getRulesArea(),
                'aid'           => $data['aid'],
                'username'      => '',
                'platform_type' => $data['platformType'],
                'spread'        => $data['spread'],
                'virtualPhone'  => $data['virtualPhone'],
                'checked'       => 1,
                'identifier'    => md5(uniqid(md5(microtime(true)), true)),
                'create_time'   => time(),
                'update_time'   => time(),
            ];
            //查询是否是强用户
            if ($user = $this->getDbUserService()->findOneBy(['oid' => $data['uid'], 'is_delete' => 0])) {
                $phoneData['username'] = $user->getTel();
                $phoneData['uid']      = $user->getId();
            }
            //插入拨打电话记录
            $addPhone = $this->getHousesTelService()->add($phoneData, null, false);
            if (!$addPhone)
                throw new BadRequestHttpException('提交数据无效');

            return $this->returnSuccess($addPhone);


        } catch (\Exception $e) {
            //打印日志
            $this->get('monolog.logger.zhuge')->error("插入拨打电话记录失败", [
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return $this->returnSuccess([]);
        }

    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createQueryForm()
    {
        return $this->createFormBuilder([
            'page'  => $this->page,
            'limit' => $this->limit,
            'city'  => 'bj',
            'order' => 'kpdate|desc',
        ], [
            'method'             => 'GET',
            'csrf_protection'    => false,
            'allow_extra_fields' => true
        ])
            ->add('page')
            ->add('limit')
            ->add('city')
            ->add('order')
            ->getForm();
    }
}