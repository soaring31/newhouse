<?php


namespace HouseBundle\Controller\Api\V1;
use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsInfoController extends RestController
{   

    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="获取购房资讯")
     *
     * @Rest\QueryParam(name="city", description="地区 city", requirements="[a-zA-Z\d]")
     * @Rest\QueryParam(name="zx_type", description="资讯类型", default="lpcp")
     *
     */
    public function getInfoNewsAction(Request $request)
    {
        $newarr = ['lpcp' => 5, 'dtzx' => 317];
        $form = $this->createQueryForm();
        $form->submit($request->query->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        $zx_type = $data['zx_type'];
        $where = [
            "city" => $data['city']
        ];
        $result = $this->getAuthGroupAreaService()->findOneBy($where);
        $newwhere = ["area" => $result->getRulesarea(), "category" => $newarr[$zx_type]];
        $newsinfo = $this->getNewsInfoService()->findBy($newwhere, [], 3, 1);
        return $this->returnSuccess($newsinfo);

    }
    /**
     * @ApiDoc(description="获取楼盘均价")
     * @Rest\QueryParam(name="lid", description="楼盘id", requirements="\d+",default="56")
     */
    public function getHousePriceAction(Request $request)
    {
        $form = $this->createBuildyForm();
        $form->submit($request->query->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        $price_where['aid'] = $data['lid'];
        /*
        $loupanprice = $this->getHousesPriceService()->findBy($price_where,[
            'create_time' => 'desc'
        ]);
        */
        $city_where = [
            'id' => $data['lid'],
        ];

        $cityid = $this->getHouseService()->findOneBy($city_where);


        $cityId=$cityid->getarea();

        $startTime = date('Y-m-01', time());
        $endTime = date('Y-m-t', time());
        $monthTrend = $this->getPricesService()->getPriceInfo($startTime,$endTime,$cityid->getarea());
        if($monthTrend['0']['countprice']){

            $areaMonthPrice=floor($monthTrend['0']['sumprice'] / $monthTrend['0']['countprice']);
        }else{
            $areaMonthPrice=0;
        }

        $areaLongMonthPrice=[];
        for($i=1;$i<13;$i++){
            $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
            $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
            $monthLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate,$longEndDate,$cityId);

            if($monthLongPreTrend['0']['sumprice']){
                if($monthLongPreTrend['0']['countprice']!='0'){
                    $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]= floor($monthLongPreTrend['0']['sumprice'] / $monthLongPreTrend['0']['countprice']);

                }
            }else{
                $areaLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;

            }
            $areajunjia=$areaLongMonthPrice[date('Y-m', strtotime("-1 month"))];
        }

        $houseLongMonthPrice=[];
        for($i=1;$i<13;$i++){
            $longStartDate=date('Y-m-01', strtotime("-{$i} month"));
            $longEndDate  =date('Y-m-t', strtotime("-{$i} month"));
            $monthLongPreTrend = $this->getHousesPriceService()->getHouseJiaGe($longStartDate,$longEndDate,$price_where['aid']);
            if($monthLongPreTrend['0']['sumprice']){
                if($monthLongPreTrend['0']['countprice']!='0'){
                    $houseLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]= floor($monthLongPreTrend['0']['sumprice'] / $monthLongPreTrend['0']['countprice']);

                }
            }else{
                   $houseLongMonthPrice[date('Y-m', strtotime("-{$i} month"))]=0;

            }

        }

        $start = date('Y-m-01', strtotime('-1 month'));
        $end =date('Y-m-t', strtotime('-1 month'));
        $pre_startdate=date('Y-m-01', strtotime('-2 month'));
        $pre_enddate  =date('Y-m-t', strtotime('-2 month'));

        //计算当前月的均价

        //计算上个月的均价

        $monthPreTrend = $this->getPricesService()->getPriceInfo($pre_startdate,$pre_enddate,$cityid->getarea());
        $areaMonthPrePrice=floor($monthPreTrend['0']['sumprice'] / $monthPreTrend['0']['countprice']);
        //环比涨幅跌幅
        $PercentAge = number_format(((($areajunjia - $areaMonthPrePrice) / $areajunjia) * 100),2);


        $month_house_pre_count = $this->getHousesPriceService()->getPriceBi($pre_startdate,$pre_enddate, $data['lid']);
        $month_house_now_count = $this->getHousesPriceService()->getPriceBi($start, $end,$data['lid']);

        if($month_house_pre_count['0']['pirce_avg'] && $month_house_now_count['0']['pirce_avg']){
            $houszhangfu = number_format(((($month_house_now_count['0']['pirce_avg'] - $month_house_pre_count['0']['pirce_avg']) / $month_house_pre_count['0']['pirce_avg']) * 100), 2);
        }else{
            $houszhangfu=0;
        }

        //计算城区均价走势
        $chengquId=$cityid->getRegion();

        $chengquNmae=$this->getAreaService()->findOneBy(['pid'=>$cityId,'id'=>$chengquId]);
        for($i=1;$i<13;$i++) {
            $longStartDate = date('Y-m-01', strtotime("-{$i} month"));
            $longEndDate = date('Y-m-t', strtotime("-{$i} month"));
            $cityMonthLongPreTrend = $this->getPricesService()->getPriceInfo($longStartDate, $longEndDate,$chengquId);
            if ($cityMonthLongPreTrend['0']['sumprice']) {
                if ($cityMonthLongPreTrend['0']['countprice'] != '0') {
                    $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))] = floor($cityMonthLongPreTrend['0']['sumprice'] / $cityMonthLongPreTrend['0']['countprice']);

                }
            } else {
                $cityLongMonthPrice[date('Y-m', strtotime("-{$i} month"))] = 0;
            }


        }
        if($cityLongMonthPrice[date('Y-m', strtotime("-1 month"))] && $cityLongMonthPrice[date('Y-m', strtotime("-2 month"))]){
            $sq_zhangfu = number_format(((($cityLongMonthPrice[date('Y-m', strtotime("-1 month"))] - $cityLongMonthPrice[date('Y-m', strtotime("-2 month"))]) / $cityLongMonthPrice[date('Y-m', strtotime("-1 month"))]) * 100), 2);
        }else{
            $sq_zhangfu=0;
        }
        $areainfo = ['zhangfu'=>$PercentAge, 'junjia'=>$areajunjia];
        empty($month_house_now_count['0']['pirce_avg'])?$monthPirceAvg=0:$monthPirceAvg=$month_house_now_count['0']['pirce_avg'];
        $houseinfo = ['zhangfu'=>$houszhangfu,'junjia'=>$monthPirceAvg];
        if($chengquNmae){
                    $areaName=$chengquNmae->getName();

        }else{
                    $areaName='';
        }
        $sqinfo=['zhangfu'=>$sq_zhangfu,'junjia'=>$cityLongMonthPrice[date('Y-m', strtotime("-1 month"))],'chengquname'=>$areaName];

        $newprice = [
            'shangquan_junjia'  =>$sqinfo,
            'area_junjia'=>$areainfo,
            'house_junjia'=>$houseinfo,
            'area_price' => $areaLongMonthPrice,
            'loupan_price' => $houseLongMonthPrice,
            'cate_price' => $cityLongMonthPrice
        ];
        return $this->returnSuccess($newprice);
    }
    /**
     * @ApiDoc(description="诸葛说-资讯列表--王晓宇")
     *
     * 王晓宇
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+")
     * @Rest\QueryParam(name="type", description="模块类型(1:热门楼讯，2：楼评导购，3:百科频道，4：诸葛周报)" , requirements="\d+")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="10")
     *
     */
    public function postMessageListsAction(Request $request)
    {
        //定义数组
        $newLists = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'city' => 'bj',
            'type' => '1',
            'page' => $this->page,
            'limit' => $this->limit,
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->add('type', 'text')
            ->add('page', 'text')
            ->add('limit', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();//获取请求数据
        $page = $data['page'];
        $limit = $data['limit'];
        //查询城区条件
        $criteria = [
            "city" => $data['city']
        ];
        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            throw new BadRequestHttpException('城市信息不存在');
        //筛选条件
        switch ($data['type']) {
            case 1:
                $newWhere['category'] = ['in'=>"325"];
                break;
            case 2:
                $newWhere['category'] = ['in'=>"5,317"];
                break;
            case 3:
                $newWhere['category'] = ['in'=>"323"];
                break;
            case 4:
                $newWhere['category'] = ['in'=>"324"];
                break;
            default:
                $newWhere['category'] = ['in'=>""];
                break;
        }
        $newWhere['area'] = $authGroupArea->getRulesarea();
        //查询资讯列表数据
        $findNewLists = $this->getNewsInfoService()->findBy(
            $newWhere,
            ['create_time'=>'desc'],
            $limit,
            $page
        );
        if (!$findNewLists['data'])
            return $this->returnSuccess([]);//返回空数据

        $houseUrl  = $this->get('service_container')->getParameter('house_url');
        //过滤数据并返回
        foreach ($findNewLists['data'] as $key => $value) {
            $newLists[$key]['id'] = $value->getId();
            $newLists[$key]['name'] = $value->getName();
            $newLists[$key]['thumb'] = $value->getThumb();
            if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $value->getThumb()) && $value->getThumb())
                $newLists[$key]['thumb'] = $houseUrl."/".$value->getThumb();
            $newLists[$key]['createtime'] = date('m-d',$value->getCreateTime());
        }

        return $this->returnSuccess($newLists,$page);
    }
    /**
     * @ApiDoc(description="诸葛说-资讯详情页--王晓宇")
     *
     * 王晓宇
     * @Rest\QueryParam(name="city", description="城市简称", requirements="\d+")
     * @Rest\QueryParam(name="newid", description="资讯ID", requirements="\d+")
     *
     */
    public function postMessageDetailsAction(Request $request)
    {
        //定义数组
        $newLists = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'city' => 'bj',
            'newid' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->add('newid', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();//获取请求数据
        if(!$data['newid'])
            throw new BadRequestHttpException('资讯ID不能为空');
        //查询城区条件
        $criteria = [
            "city" => $data['city']
        ];
        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            throw new BadRequestHttpException('城市信息不存在');
        //筛选条件
        $newWhere = ['id'=>$data['newid'],'area'=>$authGroupArea->getRulesarea()];
        //查询资讯列表数据
        $findNewLists = $this->getNewsInfoService()->findOneBy($newWhere);
        if (!$findNewLists)
            return $this->returnSuccess([]);//返回空数据

        //过滤数据并返回
        $newLists['name'] = $findNewLists->getName();
        $newLists['author'] = $findNewLists->getAuthor();
        $newLists['createtime'] = date('Y-m-d H:i:s',$findNewLists->getCreateTime());
        $newLists['content'] = $findNewLists->getContent();

        return $this->returnSuccess($newLists);
    }
    
    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createQueryForm()
    {
        return $this->createFormBuilder([
            'city' => 'bj',
            'zx_type' => 'lpcp',
        ], [
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
            ->add('city')
            ->add('zx_type')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createBuildyForm()
    {
        return $this->createFormBuilder([
            'lid' => '2436',

        ], [
            'method' => 'GET',
            'csrf_protection' => false
        ])
            ->add('lid')
            ->getForm();
    }
    /**
     * @ApiDoc(description="武运晓--------------获取置业顾问")
     * @Rest\RequestParam(name="aid", description="楼盘id" )
     * @Rest\RequestParam(name="city", description="城市简称" )
     *
     */
    public function postFindAdviserAction(Request $request)
    {
        $form = $this->createFormBuilder([
            'aid' => '',
            'city' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])

            ->add('aid', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'楼盘ID不能为空']),
                ]
            ])
            ->add('city', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'城市ID不能为空']),
                ]
            ])
            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();
        $criteria=['city'=>$data['city']];
        $authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria);

        $cityId=$authGroupArea->getRulesarea();
        $whereInfo=[
            'id'=>$data['aid'],
            'area'=>$cityId,
        ];

        $houseInfo=$this->getHouseService()->findOneBy($whereInfo);

        if(empty($houseInfo)){
            throw new BadRequestHttpException('楼盘信息不存在');
        }
        $oldId= $houseInfo->getComplexId();

        $api_url = $this->get('service_container')->getParameter('api_url');



        $sell_house_price_url = $api_url . "/API/NewHouseBorough/getNewBorkerTel";
        $data = array(
            "city" =>$data['city'],
            "complex_id" =>$oldId,
        );
        $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url, $data);
        $sell_data = json_decode($sell_house_price_data, true);

        
        if(empty($sell_data['data'])){
            throw new BadRequestHttpException('置业顾问不存在');
        }
        return $this->returnSuccess($sell_data);

    }


    /**
     * @ApiDoc(description="诸葛说-列表--刘凌旭")
     *
     * 刘凌旭
     * @Rest\QueryParam(name="city", description="城市简称", requirements="[a-zA-Z\d]")
     * @Rest\QueryParam(name="modular", description="分类(zgbk：百科，zgzb:周报，zgtt：头条)" , requirements="[a-zA-Z\d]")
     * @Rest\QueryParam(name="pageStart", description="页码", requirements="\d+", default="1")
     * @Rest\QueryParam(name="pagelimit", description="数量", requirements="\d+", default="10")
     *
     */
    public function postNewsListAction(Request $request)
    {
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'city'  => 'bj',
            'modular'  => 'zgbk',
            'pageStart'  => $this->page,
            'pageLimit' => $this->limit,
        ], [
            'method'          => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->add('modular', 'text')
            ->add('pageStart', 'text')
            ->add('pageLimit', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();//获取请求数据
        //定义栏目
        $category = [
            'zgbk' => 323, //百科
            'zgzb' => 324, //周报
            'zgtt' => 325 , //头条
//            'dtzx' => 317 //动态资讯
        ];

        //筛选条件
        if (isset($category[$data['modular']])){
            $new['category'] = ['in' => $category[$data['modular']]];
        }else{
            throw new BadRequestHttpException('栏目参数异常');
        }

        //定义城市
        $criteria = [
            "city" => $data['city']
        ];
        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria)){
            $new['area'] = ['in' => "1,0"];
            //查询资讯原始列表数据
            $findNewLists = $this->getNewsInfoService()->findBy(
                $new,
                ['create_time'=>'desc'],
                $data['pageLimit'],
                $data['pageStart']
            );
        }else{
            $new['area'] = ['in' => "{$authGroupArea->getRulesarea()}"];
            //查询资讯原始列表数据
            $findNewLists = $this->getNewsInfoService()->findBy(
                $new,
                ['create_time'=>'desc'],
                $data['pageLimit'],
                $data['pageStart']
            );
            //判断是否有数据
            if ($findNewLists['data'] === []){
                $new['area'] = ['in' => "1,0"];
            }else{
                $new['area'] = ['in' => "{$authGroupArea->getRulesarea()},0"];;
            }
            $findNewLists = $this->getNewsInfoService()->findBy(
                $new,
                ['create_time'=>'desc'],
                $data['pageLimit'],
                $data['pageStart']
            );
        }

         //定义所需资讯空数组
        $newLists = [];
        //获取域名
        $houseUrl = $this->get('service_container')->getParameter('house_url');
        //过滤数据并返回
        foreach ($findNewLists['data'] as $key => $value)
        {
            $newLists[$key]['id'] = $value->getId();
            $newLists[$key]['username'] = $value->getAuthor();
            $newLists[$key]['title'] = $value->getName();
            $newLists[$key]['description'] = $value->getAbstract();
            $newLists[$key]['inputtime'] = $value->getCreateTime();
            if (strstr($value->getThumb(),'http')){
                $newLists[$key]['thumb'] =  $value->getThumb();
            }else{
                $newLists[$key]['thumb'] = $houseUrl . "/" . $value->getThumb();
            }
            if (strstr($houseUrl,'newhouse')){
                $newLists[$key]['url'] = $houseUrl . "/news/detail/{$value->getId()}.html?cy=app";
            }else{
                $newLists[$key]['url'] = $houseUrl . "/app_dev.php/news/detail/{$value->getId()}.html?cy=app";
            }
            $newLists[$key]['content'] = $value->getContent();
            $newLists[$key]['pc_url'] = $houseUrl . "/news/detail/{$value->getId()}.html";
        }
        $the_new_lists['list'] = $newLists;

        //分页缓存
        if ($this->get('core.common')->S($data['modular'].'_'.$data['city'].'_'.$data['pageStart'].'_'.$data['pageLimit'])){
            //cache有的话走cache
            $tempContent = $this->get('core.common')->S($data['modular'].'_'.$data['city'].'_'.$data['pageStart'].'_'.$data['pageLimit']);
            return $this->returnSuccess($tempContent, $data['pageStart']);
        }else{
            //如果cache没有的话存cache
            $this->get('core.common')->S($data['modular'].'_'.$data['city'].'_'.$data['pageStart'].'_'.$data['pageLimit'], $the_new_lists, 600);
            return $this->returnSuccess($the_new_lists, $data['pageStart']);
        }

    }
}