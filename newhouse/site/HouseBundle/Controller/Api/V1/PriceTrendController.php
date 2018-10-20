<?php

namespace HouseBundle\Controller\Api\V1;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\Regex;

class PriceTrendController extends RestController
{
    private $page = 1;
    private $limit = 10;
    /**
     * @ApiDoc(description="武运晓-----根据简称获取城区")
     * @Rest\QueryParam(name="city", description="城市简称")
     *
     */
    public function postCityinAreaAction(Request $request)
    {
        $form = $this->createFormBuilder([
            'city' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        $cityId=$this->getAuthGroupAreaService()->findOneBy(['city'=>$data['city']]);
        if($cityId){
            $cityPid=$cityId->getRulesArea();
        }
        if($cityId){
            return $this->returnSuccess($this->getAreaService()->findBy(['pid'=>$cityPid]));
        }else{
            throw new BadRequestHttpException('城市信息不存在');
        }
    }
    /**
     *
     * @ApiDoc(description="武运晓-----根据城区获取商圈")
     * @Rest\QueryParam(name="area", description="城区ID")
     *
     */
    public function postAreainCateAction(Request $request)
    {
        $form = $this->createFormBuilder([
            'area' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('area', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data=$form->getData();
        $cityInfo=$this->getAreaService()->findOneBy(['id'=>$data['area']]);
        $cityId=$cityInfo->getPid();
        $cateInfo=$this->getCateCircleService()->findBy(['pid'=>$data['area'],'area'=>$cityId]);
        if($cateInfo){
            return $this->returnSuccess($cateInfo);
        }else{
            throw new BadRequestHttpException('商圈信息不存在');
        }
    }
    /**
     * @ApiDoc(description="武运晓------------城市房价走势数据")
     *
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+", default="bj")
     * @Rest\QueryParam(name="begin_price", description="价格开始", requirements="\w+")
     * @Rest\QueryParam(name="end_price", description="价格结束", requirements="\w+")
     *
     */
    public function postCityTrendAction(Request $request)
    {
        $form =  $this->createFormBuilder([
            'begin_price' => '',
            'end_price' => '',
            'city' => 1,
        ], [
            'method' => 'POST',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
            ->add('city')
            ->add('begin_price')
            ->add('end_price')
            ->getForm();
        $form->submit($request->request->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        $cityId=$this->getAuthGroupAreaService()->findOneBy(['city'=>$data['city']]);
        $cityId=$cityId->getRulesArea();

        //计算当前月的均价
        $startTime = date('Y-m-01', time());
        $endTime = date('Y-m-t', time());
        $monthTrend = $this->getPricesService()->getPriceInfo($startTime,$endTime,$cityId);
        $areaMonthPrice=floor($monthTrend['0']['sumprice'] / $monthTrend['0']['countprice']);
        //计算上个月的均价
        $preStartDate=date('Y-m-01', strtotime('-1 month'));
        $preEndDate  =date('Y-m-t', strtotime('-1 month'));
        $monthPreTrend = $this->getPricesService()->getPriceInfo($preStartDate,$preEndDate,$cityId);
        $areaMonthPrePrice=floor($monthPreTrend['0']['sumprice'] / $monthPreTrend['0']['countprice']);
        //环比涨幅跌幅
        $PercentAge = number_format(((($areaMonthPrice - $areaMonthPrePrice) / $areaMonthPrice) * 100),2);
        //统计所属城区的房子
        $houseCount = $this->getHouseService()->count(['area'=>$cityId]);

        //计算6个月城市的均价
        $areaLongMonthPrice=[];
        for($i=1;$i<6;$i++){
            $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
            $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
            $monthLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate,$longEndDate,$cityId);
            $areaLongMonthPrice[date('Y-m', time())]=$areaMonthPrice;
            $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=floor($monthLongPreTrend['0']['sumprice'] / $monthLongPreTrend['0']['countprice']);
        }
        //获取城市的价格区间
        $areaPriceIn = $this->getCategoryService()->findBy(['ename'=>'dj']);
        if($areaPriceIn) {
            foreach ($areaPriceIn['data'] as &$areaPriceIn) {
                $areaPriceInTo[]=['id' => $areaPriceIn->getId(),
                    'id' => $areaPriceIn->getId(),
                    'name' => $areaPriceIn->getName(),
                    'sqlstr' => $areaPriceIn->getSqlstr(),

                ];

            }
        }
        /*
        //设置价格区间的默认值,判断是否传参数
        if(empty($data['begin_price']) && empty($data['end_price'])){
            $defalutPrice = explode("|", $areaPriceInTo['0']['sqlstr']);
                if (strstr(',',$defalutPrice[1])) {
                    $newArr=explode(",", $defalutPrice);
                    $beginPrice=$newArr[0];
                    $endPrice=$newArr[1];

                } else {
                    $beginPrice=$defalutPrice[1];
                    $endPrice  ='';
        }
        }
        else{
                $beginPrice=$data['begin_price'];
                $endPrice  =$data['end_price'];
        }*/
        //计算6个月城区的均价
        $cityLongMonthPrice=[];
        $cityArea = $this->getAreaService()->findBy(['pid'=>$cityId],[],3,1);

        foreach($cityArea['data'] as $cityArea){
            for($i=0;$i<6;$i++){
                $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
                $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
                $cityMonthLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate,$longEndDate,$cityArea->getId());
                if($cityMonthLongPreTrend['0']['sumprice']){
                    if($cityMonthLongPreTrend['0']['countprice']!='0'){
                        $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]= floor($cityMonthLongPreTrend['0']['sumprice'] / $cityMonthLongPreTrend['0']['countprice']);

                    }
                }else{
                        $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;

                }
            }

                  $houseWhere=[
                            "area"=>$cityId,
                            "region"=>$cityArea->getId(),
                            /*
                            'dj' => [
                                'between' => [sprintf("'%s'", $beginPrice), sprintf("'%s'", $endPrice)]
                            ]*/
                    ];
                    /*
                //根据价格和城市查询3个小区
                if($beginPrice && $endPrice){
              
                }else{
                    $houseWhere=[
                        "area"=>$cityId,
                        "region"=>$cityArea->getId(),
                        
                        'dj' => [
                            'lt' =>$beginPrice
                        ]
                    ];
                }*/
            $cityHouses= $this->getHouseService()->findBy($houseWhere,[
                'kpdate' => 'desc'
            ], 3,1);
            foreach ($cityHouses['data'] as $k => $v){
                $v->setCateStatus($this->getCategoryService()->findCateGory($v->getCateStatus()));
                $v->setCateType($this->getCategoryService()->findCateGory($v->getCateType()));
            }
            //拼接城区价格走势和城区的房子

           // $preStartDate=date('Y-m', strtotime('-1 month'));
            $areaInfo=['name'=>$cityArea->getName(),'junjia'=>$cityLongMonthPrice[$startTime = date('Y-m', time())]];
            $cityPriceAndHouse[]=["areainfo"=>$areaInfo,"priceorder"=>$cityLongMonthPrice,"cityhouse"=>$cityHouses['data']];
        }


        $allPriceInfo = ['zhangfu'=>$PercentAge, 'junjia'=>$areaMonthPrice, 'housecount'=>$houseCount,'longmonthprice'=>$areaLongMonthPrice,'areapricein'=>$areaPriceInTo,"citypriceandhouse"=>$cityPriceAndHouse];
        return $this->returnSuccess($allPriceInfo);
    }
    /**
     * @ApiDoc(description="武运晓------------城区房价走势数据")
     *
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+", default="bj")
     * @Rest\QueryParam(name="area", description="城区id", requirements="\d+", default="1")
     * @Rest\QueryParam(name="begin_price", description="价格开始", requirements="\w+")
     * @Rest\QueryParam(name="end_price", description="价格结束", requirements="\w+")
     *
     */
    public function postAreaTrendAction(Request $request)
    {
        $form =  $this->createFormBuilder([
            'begin_price' => '',
            'end_price' => '',
            'city' => 1,
            'area' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
            ->add('city')
            ->add('area')
            ->add('begin_price')
            ->add('end_price')
            ->getForm();
        $form->submit($request->request->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        $cityId=$this->getAuthGroupAreaService()->findOneBy(['city'=>$data['city']]);
        $cityId=$cityId->getRulesArea();
        $areaId=$data['area'];
        //计算当前月的均价
        $startTime = date('Y-m-01', time());
        $endTime = date('Y-m-t', time());
        $monthTrend = $this->getPricesService()->getPriceInfo($startTime,$endTime,$areaId);

        $areaMonthPrice=floor($monthTrend['0']['sumprice'] / $monthTrend['0']['countprice']);

        //计算上个月的均价
        $preStartDate=date('Y-m-01', strtotime('-1 month'));
        $preEndDate  =date('Y-m-t', strtotime('-1 month'));
        $monthPreTrend = $this->getPricesService()->getPriceInfo($preStartDate,$preEndDate,$areaId);
        $areaMonthPrePrice=0;
        if($monthPreTrend['0']['countprice']!=0){
                 $areaMonthPrePrice=floor($monthPreTrend['0']['sumprice'] / $monthPreTrend['0']['countprice']);
        }

        //计算涨幅
        $PercentAge = number_format(((($areaMonthPrice - $areaMonthPrePrice) / $areaMonthPrice) * 100),2);


        //统计所属区域的房子
        $houseCount = $this->getHouseService()->count(['area'=>$cityId,"region"=>$areaId]);
        //计算6个月城市的均价
        $areaLongMonthPrice=[];
        for($i=0;$i<6;$i++){
            $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
            $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
            //计算城市的均价
            $cityMonthLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate,$longEndDate,$cityId);
            if($cityMonthLongPreTrend['0']['sumprice']){
                 $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=floor($cityMonthLongPreTrend['0']['sumprice'] / $cityMonthLongPreTrend['0']['countprice']);
            }else{
                 $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;
            }
            //计算城区的均价
            $areahLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate,$longEndDate,$areaId);
            if($areahLongPreTrend['0']['sumprice']){
                $citisLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=floor($areahLongPreTrend['0']['sumprice'] / $areahLongPreTrend['0']['countprice']);
            }else{
                $citisLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;
            }
        }

        //获取城市的价格区间
        $areaPriceIn = $this->getCategoryService()->findBy(['ename'=>'dj']);
        if($areaPriceIn) {
            foreach ($areaPriceIn['data'] as &$areaPriceIn) {
                $areaPriceInTo[]=['id' => $areaPriceIn->getId(),
                    'id' => $areaPriceIn->getId(),
                    'name' => $areaPriceIn->getName(),
                    'sqlstr' => $areaPriceIn->getSqlstr(),

                ];

            }
        }
        //设置价格区间的默认值,判断是否传参数
        if(empty($data['begin_price']) && empty($data['end_price'])){
            $defalutPrice = explode("|", $areaPriceInTo['0']['sqlstr']);
            if (strstr(',',$defalutPrice[1])) {
                $newArr=explode(",", $defalutPrice);
                $beginPrice=$newArr[0];
                $endPrice=$newArr[1];

            } else {
                $beginPrice=$defalutPrice[1];
                $endPrice  ='';
            }
        }
        else{
            $beginPrice=$data['begin_price'];
            $endPrice  =$data['end_price'];
        }
        //计算6个月商圈的均价
        $chengquLongMonthPrice=[];
        $cityArea = $this->getCateCircleService()->findBy(['pid'=>$areaId,"area"=>$cityId],[],3,1);

        foreach($cityArea['data'] as $cityArea){
            $areaHouseId = $this->getHouseService()->findBy(['cate_circle'=>$cityArea->getId(),"area"=>$cityId]);
            $id='';
            if($areaHouseId){
                foreach($areaHouseId['data'] as &$areaHouseId){
                            $id.=$areaHouseId->getId().',';
                }
            }
            for($i=0;$i<6;$i++){
                $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
                $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
                $cityMonthLongPreTrend = $this->getHousesPriceService()->getAreaPrice($longStartDate,$longEndDate,$id);

                if($cityMonthLongPreTrend['0']['sumprice']){
                    if($cityMonthLongPreTrend['0']['countprice']!='0'){
                        $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]= floor($cityMonthLongPreTrend['0']['sumprice'] / $cityMonthLongPreTrend['0']['countprice']);

                    }
                }else{
                    $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;

                }
            }

            //根据价格和城市查询3个小区
            if($beginPrice && $endPrice){
                $houseWhere=[
                    "area"=>$cityId,
                    "cate_circle"=>$cityArea->getId(),
                    'dj' => [
                        'between' => [sprintf("'%s'", $beginPrice), sprintf("'%s'", $endPrice)]
                    ]
                ];
            }else{
                $houseWhere=[
                    "area"=>$cityId,
                    "cate_circle"=>$cityArea->getId(),
                    'dj' => [
                        'lt' =>$beginPrice
                    ]
                ];
            }
            $areaHouses= $this->getHouseService()->findBy($houseWhere,[
                'kpdate' => 'desc'
            ], 3,1);
            foreach ($areaHouses['data'] as $k => $v){
                $v->setCateStatus($this->getCategoryService()->findCateGory($v->getCateStatus()));
                $v->setCateType($this->getCategoryService()->findCateGory($v->getCateType()));
            }


            //拼接城区价格走势和城区的房子
            $cateInfo=['name'=>$cityArea->getName(),'junjia'=>$cityLongMonthPrice[$startTime = date('Y-m', time())]];
            $areaPriceAndHouse[]=['areainfo'=>$cateInfo,"priceorder"=>$cityLongMonthPrice,"cityhouse"=>$areaHouses['data']];
        }

        $allPriceInfo = ['zhangfu'=>$PercentAge, 'junjia'=>$areaMonthPrice, 'housecount'=>$houseCount,'areapricein'=>$areaPriceInTo,'sixcitymothprice'=>$areaLongMonthPrice,'sixareamothprice'=>$citisLongMonthPrice,"citypriceandhouse"=>$areaPriceAndHouse];
        return $this->returnSuccess($allPriceInfo);
    }
    /**
     * @ApiDoc(description="武运晓------------商圈房价走势数据")
     *
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+", default="bj")
     * @Rest\QueryParam(name="cate", description="商圈id", requirements="\d+", default="1")
     *
     */
    public function postCateTrendAction(Request $request)
    {
        $form =  $this->createFormBuilder([
            'city' => 1,
            'cate' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
            ->add('city')
            ->add('cate')
            ->getForm();
        $form->submit($request->request->all(), false);

        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        $cityId=$this->getAuthGroupAreaService()->findOneBy(['city'=>$data['city']]);
        $cityId=$cityId->getRulesArea();
        $cateId=$data['cate'];
        //计算当前月城市6个月均价
        $areaLongMonthPrice=[];
        for($i=0;$i<6;$i++){
            $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
            $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
            //计算城市的均价
            $cityMonthLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate,$longEndDate,$cityId);
            if($cityMonthLongPreTrend['0']['sumprice']){
                $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=floor($cityMonthLongPreTrend['0']['sumprice'] / $cityMonthLongPreTrend['0']['countprice']);
            }else{
                $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;
            }
        }


            //计算6个月商圈的均价

            $cityLongMonthPrice=[];
            $areaHouseId = $this->getHouseService()->findBy(['cate_circle'=>$cateId,"area"=>$cityId]);

           if(empty($areaHouseId['data'])){
               throw new BadRequestHttpException('收藏失败');
           }
            $houseCount=count($areaHouseId['data']);
            $id='';
            if($areaHouseId){
                foreach($areaHouseId['data'] as &$areaHouseId){
                    $id.=$areaHouseId->getId().',';
                }
            }

            for($i=0;$i<6;$i++){
                $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
                $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
                $cityMonthLongPreTrend = $this->getHousesPriceService()->getAreaPrice($longStartDate,$longEndDate,$id);
                if($cityMonthLongPreTrend['0']['sumprice']){
                    if($cityMonthLongPreTrend['0']['countprice']!='0'){
                        $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]= floor($cityMonthLongPreTrend['0']['sumprice'] / $cityMonthLongPreTrend['0']['countprice']);

                    }
                }else{
                        $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;

                }
            }

            $houseWhere=[
                "area"=>$cityId,
                "cate_circle"=>$cateId,

            ];

            $cateHouses= $this->getHouseService()->findBy($houseWhere,[
                'kpdate' => 'desc'
            ], 3,1);
        foreach ($cateHouses['data'] as $k => $v){
            $v->setCateStatus($this->getCategoryService()->findCateGory($v->getCateStatus()));
            $v->setCateType($this->getCategoryService()->findCateGory($v->getCateType()));
        }
        $startTime = date('Y-m', time());
        $preStartDate=date('Y-m', strtotime('-1 month'));
       if($cityLongMonthPrice[$startTime]!=0){
            $areaAddPrice = number_format(((($cityLongMonthPrice[$startTime] - $cityLongMonthPrice[$preStartDate]) / $cityLongMonthPrice[$startTime]) * 100),2);

        }
        else{
            $areaAddPrice=0;
        }
        $cateArea = $this->getCateCircleService()->findOneBy(['id'=>$cateId,'area'=>$cityId]);

        $cateInfo=['name'=>$cateArea->getName(),'junjia'=>$cityLongMonthPrice[$startTime = date('Y-m', time())]];
        $allPriceInfo = ['zhangfu'=>$areaAddPrice, 'junjia'=>$cityLongMonthPrice[$startTime],'housecount'=>$houseCount, 'housecount'=>$houseCount,'areainfo'=>$cateInfo,'sixcitymothprice'=>$areaLongMonthPrice,'sixareamothprice'=>$cityLongMonthPrice,"catehouse"=>$cateHouses['data']];
        return $this->returnSuccess($allPriceInfo);
    }
}