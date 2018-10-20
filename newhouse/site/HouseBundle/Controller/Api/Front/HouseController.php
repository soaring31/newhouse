<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/11
 * Time: 16:32
 */

namespace HouseBundle\Controller\Api\Front;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\Houses;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HouseController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="前端楼盘详情页获取楼盘信息")
     *
     * @Rest\QueryParam(name="area", description="地区id", requirements="\d+", default="1")
     *
     * @Rest\QueryParam(name="begin_kpdate", description="开盘开始时间", requirements="\w+")
     * @Rest\QueryParam(name="end_kpdate", description="开盘结束时间", requirements="\w+")
     *
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="15")
     *
     */
    public function getHousesInfoAction(Request $request)
    {
        $form = $this->createQueryForm();
        $form->submit($request->query->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data        = $form->getData();
        $beginKpdate = $data['begin_kpdate'];
        $endKpdate   = $data['end_kpdate'];

        if (!$beginKpdate || !$endKpdate)
            throw new BadRequestHttpException('楼盘开盘时间, 结束时间必须存在');

        $page  = $data['page'];
        $limit = $data['limit'];

        $criteria = [
            "area"        => $data['area'],
            'cate_status' => 309,
            'kpdate'      => [
                'between' => [sprintf("'%s'", $beginKpdate), sprintf("'%s'", $endKpdate)]
            ]
        ];

        $houses = $this->getHouseService()->findBy($criteria, [
            'kpdate' => 'desc'
        ], $limit, $page);

        return $this->returnSuccess($houses, $page);
    }

    /**
     * @ApiDoc(description="前端楼盘详情页获取全网比价")
     *
     * @Rest\QueryParam(name="id", description="楼盘id", requirements="\d+", default="1")
     *
     */
    public function getHouseGlobeCompareAction(Request $request)
    {
        $id = $request->query->get('id', '');
        if (!$id || !$house = $this->getHouseService()->findOneBy(['id' => $id]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!$house->getComplexId())
            throw new NotFoundHttpException('楼盘比价信息不存在');

        $area = $house->getArea();
        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy(['rulesarea' => $area]))
            throw new NotFoundHttpException('楼盘地区不存在');

        if (!$city = $authGroupArea->getCity())
            throw new NotFoundHttpException('楼盘地区所在城市简称不存在');

        $url         = $this->get('service_container')->getParameter('api_url') . "/API/NewHouseBorough/getComplexGov";
        $compareInfo = $this->get('request.handler')->curl_post($url, [
            'city'       => $city,
            'complex_id' => $house->getComplexId()
        ]);

        $compareInfo = json_decode($compareInfo, true);

        if ($compareInfo['code'] == 200) {
            $compareInfo = empty($compareInfo['data']['list']) ? [] : $compareInfo['data']['list'];
        } else {
            $compareInfo = [];
        }

        return $this->returnSuccess($compareInfo);
    }

    /**
     * @ApiDoc(description="前端楼盘获取随机热线")
     *
     * @Rest\QueryParam(name="id", description="楼盘id", requirements="\d+", default="1")
     *
     */
    public function getHouseHotlineAction(Request $request)
    {
        $id = $request->query->get('id', '');
        if (!$id || !$house = $this->getHouseService()->findOneBy(['id' => $id]))
            throw new NotFoundHttpException('楼盘信息不存在');

        if (!($house instanceof Houses))
            throw new \LogicException('楼盘数据错误');

        if (!$hotLines = $house->getHotLine())
            return $this->returnSuccess([]);

        $hotLines = array_unique(array_filter(explode('#', $hotLines)));
        $counter  = count($hotLines);
        $index    = rand(0, $counter - 1);

        $hotLine = $hotLines[$index];

        if ($hotLineInfo = array_filter(explode(',', $hotLine))) {
            if (count($hotLineInfo) > 1) {
                $hotLine = implode('转', $hotLineInfo);
            } else {
                $hotLine = current($hotLineInfo);
            }
        }

        return $this->returnSuccess($hotLine);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createQueryForm()
    {
        return $this->createFormBuilder([
            'begin_kpdate' => '',
            'end_kpdate'   => '',
            'area'         => 1,
            'page'         => $this->page,
            'limit'        => $this->limit,
        ], [
            'method'             => 'GET',
            'csrf_protection'    => false,
            'allow_extra_fields' => true
        ])
            ->add('area')
            ->add('begin_kpdate')
            ->add('end_kpdate')
            ->add('page')
            ->add('limit')
            ->getForm();
    }

    /**
     * @ApiDoc(description="更新数据(更新热线, 售楼地址, 开盘时间)",views={"sync"})
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @throws \Exception
     */
    public function getSyncDataAction()
    {
        return $this->getSyncHandler()->syncHousesInfo();
    }

    public function getSync400DataAction()
    {
//        $this->getHouseHandler()->updateServiceTel()
    }

    /**
     * @ApiDoc(description="前端楼盘详情页---楼盘详情--王晓宇")
     *
     * @Rest\QueryParam(name="aid", description="楼盘ID", requirements="\d+", default="1")
     *
     */
    public function getHousesDetailsAction(Request $request)
    {
        try {
            //接收参数
            $aid = $request->query->get('aid', '');

            //查询楼盘价格
            $housePrice = $this->getHousesPriceService()->findBy(['aid'=>$aid]);
            //查询交房记录
            $houseRecord = $this->getHouseMakeService()->findBy(['aid'=>$aid]);
            //查询楼盘详情数据
            $house = $this->getHouseService()->findOneBy(['id' => $aid,'checked'=>1,'is_delete'=>0]);
            if($house){
                //查询小区数据
                $houseAttr = $this->getHouseHandler()->getAttrInfo($house);
            }
            $options = $this->get('service_container')->getParameter('options');

            //定义数组
            $houseInfo = [
                'basicData' => [],//基本信息
                'saleData' => [],//销售信息
                'houseRecord' => $houseRecord['data'],//交房记录
                'periphery' => [],//周边设施
                'districtPlanning' => [],//小区规划
                'priceData' => $housePrice['data'],//价格信息
            ];

            if ($house) {

                //基本信息
                //定义默认值
                $houseInfo['basicData']['overall'] = '暂无数据';
                $houseInfo['basicData']['cate_type'] = '暂无数据';//物业类型
                $houseInfo['basicData']['zxcd'] = '暂无数据';
                $houseInfo['basicData']['property_year'] = '暂无数据';
                $houseInfo['basicData']['cate_circle'] = "暂无数据";
                $houseInfo['basicData']['region'] = "暂无数据";
                $houseInfo['saleData']['cate_status'] = '暂无数据';
                $houseInfo['saleData']['zxjf'] = "暂无数据";
                $houseInfo['saleData']['discount'] = "暂无数据";//楼盘优惠
                $houseInfo['districtPlanning']['buildDate'] = "暂无数据";//楼栋信息
                $houseInfo['districtPlanning']['provide'] = "暂无数据";//水电燃气
                $houseInfo['districtPlanning']['provide_heating'] = "暂无数据";//供暖

                //查询分类的数据
                $cateStatus = [];
                array_push($cateStatus, $house->getCateType());
                array_push($cateStatus, $house->getCateStatus());
                array_push($cateStatus, $house->getZxcd());
                $typeWhere = ['id' => ['in' => array_unique($cateStatus)]];
                $findCates = $this->getCategoryService()->findBy($typeWhere);
                if ($findCates) {
                    foreach ($findCates['data'] as $k => $v) {
                        if ($house->getCateType() == $v->getId())
                            $houseInfo['basicData']['cate_type'] = $v->getName();
                        if ($house->getCateStatus() == $v->getId())
                            $houseInfo['saleData']['cate_status'] = $v->getName();
                        if ($house->getZxcd() == $v->getId())
                            $houseInfo['basicData']['zxcd'] = $v->getName();
                    }
                }

                $houseInfo['basicData']['dj'] = $house->getDj() ? sprintf("均价约%s元/平", $house->getDj()) : "暂无数据";
                if ($houseComment = $this->getInterHousesCommentService()->findOneBy(['aid' => $aid]))
                    $houseInfo['basicData']['overall'] = sprintf("%s分", $houseComment->getOverall());

                $houseInfo['basicData']['tslp'] = $this->getHouseService()->getTslp($house->getTslp());
                //查询楼盘附属信息
                $houseSubsidiary = $this->getHouseInfoService()->findOneBy(['aid' => $aid]);
                if ($houseSubsidiary){
                    $houseInfo['basicData']['build_type'] = $houseSubsidiary ? $this->getValidData($options['building_type'], $houseSubsidiary->getComplexBuildingType()) : '暂无数据';
                    $houseInfo['basicData']['architectural'] = $houseSubsidiary ? $this->getValidData($options['architectural'], $houseSubsidiary->getArchitectural()) : '暂无数据';
                    $houseInfo['basicData']['property_year'] = $houseSubsidiary->getPropertyYear();
                }
                $houseInfo['basicData']['hxs'] = $this->getHouseService()->getHxs($house->getHxs());//环线
                $houseInfo['basicData']['house_developer'] = $house->getHouseDeveloper();
                $houseInfo['basicData']['address'] = $house->getAddress();

                //查询商圈的数据
                if($findCateCircles = $this->getCateCircleService()->findOneBy(['id'=>$house->getCateCircle()]))
                    $houseInfo['basicData']['cate_circle'] = $findCateCircles->getName();
                //查询城区的数据
                if($findRegion  = $this->getAreaService()->findOneBy(['id'=>$house->getRegion()]))
                    $houseInfo['basicData']['region'] = $findRegion->getName();

                //销售信息
                $houseInfo['saleData']['kpdate'] = $house->getKpDate();
                if($houseRecord['data'][0])
                    $houseInfo['saleData']['zxjf'] = $houseRecord['data'][0]->getJfrq();
                $houseInfo['saleData']['sldz'] = $house->getSldz();
                //PC销售电话
                if($house->getPcTel()){
                    $pcTel = $house->getPcTel();
                }else{
                    $pcTel = $house->getTel();
                }
                $houseInfo['saleData']['pc_tel'] = $pcTel;
                //H5销售电话
                if($house->getWebTel()){
                    $webTel = $house->getWebTel();
                }else{
                    $webTel = $house->getTel();
                }
                $houseInfo['saleData']['web_tel'] = $webTel;
                //查询户型的数据
                $findDoor = $this->getDoorsService()->getDoorSum($house->getId());
                $t = '';
                foreach ($findDoor as $k=>&$v){
                    if($findShi = $this->getCategoryService()->findOneBy(['ename'=>'shi','id'=>$v['shi']]))
                        $v['name'] = $findShi->getName();
                    unset($v['shi']);
                    $t.= $v['name']."(".$v['counter'].")".",";
                }
                $houseInfo['saleData']['shi'] = trim($t,",");
                //获取楼栋信息
                $build = $this->getHouseHandler()->getBuilding($house);
                if($build['data']){
                    $zkzhs = [];
                    foreach ($build['data'] as $k => $v){
                        $hash = md5(json_encode([$v->getXkzh(), $v->getXkzhTime()]));
                        if(isset($zkzhs[$hash]['builds'])){
                            array_push($zkzhs[$hash]['builds'], $v->getName());
                        }else{
                            $zkzhs[$hash]['builds'] = [$v->getName()];
                            $zkzhs[$hash]['xkzh'] = $v->getXkzh();
                            $zkzhs[$hash]['fzsj'] = $v->getXkzhTime();
                        };
                    }
                    //把楼栋合并成字符串
                    foreach ($zkzhs as &$v){
                        $v['builds'] = implode(" ",$v['builds']);
                    }
                    $houseInfo['saleData']['builds'] = $zkzhs;
                }

                //小区规划
                $houseInfo['districtPlanning'] = $houseAttr;
                $houseInfo['districtPlanning']['totalhushu'] = '';//总户数
                $houseInfo['districtPlanning']['buildsum'] = count($build['data']);//楼栋总数
                $houseInfo['districtPlanning']['floorstatus'] = '';//楼层状况
                $houseInfo['districtPlanning']['buildDate'] = $build['text'];//楼栋信息
                $houseInfo['districtPlanning']['wyms'] = '';//物业描述
                if ($houseSubsidiary){
                    $houseInfo['districtPlanning']['provide'] = $houseSubsidiary->getProvideWater() . $houseSubsidiary->getProvideElectric() . $houseSubsidiary->getProvideGas();//水电燃气
                    $houseInfo['districtPlanning']['provide_heating'] = $houseSubsidiary->getProvideHeating();//供暖
                }
                //周边配置
                $around = $this->getHouseHandler()->getAround($house);
                foreach ($around as $k=>&$v){
                    foreach ($v as $key=>&$value){
                        $value = strip_tags(str_replace('&nbsp;','',$value));
                    }
                }
                $houseInfo['periphery'] = $around;

            }

            if(!$houseInfo)
                return $this->returnSuccess([]);

            return $this->returnSuccess($houseInfo);

        } catch (\Exception $e) {
            //打印日志
            $this->get('monolog.logger.zhuge')->error("查询详情页数据失败",[
                'file'=>$e->getFile(),
                'line'=>$e->getLine(),
                'message'=>$e->getMessage()
            ]);
            return $this->returnSuccess([]);
        }
    }
}