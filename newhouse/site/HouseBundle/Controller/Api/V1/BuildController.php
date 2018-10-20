<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/10
 * Time: 17:57
 */

namespace HouseBundle\Controller\Api\V1;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Services\DB\Category;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
class BuildController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="详情页楼栋-获取楼栋数据--王晓宇")
     *
     *
     * @Rest\QueryParam(name="aid", description="楼盘ID required") 
     * @Rest\QueryParam(name="cstatus", description="销售状态 unrequired")
     *
     */
    public function getBuildListAction(Request $request)
    {
        //定义数组
        $buildList = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'aid' => '',
            'cstatus' => '',
        ], [
            'method' => 'GET',
            'csrf_protection' => false
        ])
            ->add('aid', 'text')
            ->add('cstatus', 'text')
            ->getForm();

        $form->submit($request->query->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();//获取请求数据

        if (!$data['aid'])
            throw new BadRequestHttpException('楼盘ID不能为空');

        $findDzsp = $this->getHouseService()->findOneBy(array('is_delete'=>0,'id'=>$data['aid']));//查询沙盘图

        //查询楼栋的数据
        //查询条件
        $where = array('is_delete'=>0);
        if($data['aid'])
            $where['aid'] = $data['aid'];
        if($data['cstatus'])
            $where['cate_status'] = $data['cstatus'];
        $findBuild = $this->getBuildsService()->findBy($where);
        if(empty($findBuild['data']))
            return $this->returnSuccess([]);//返回空数据

        $cateStatus = [];//定义分类的数组
        $buildDoors = '';//定义户型的数组
        foreach ($findBuild['data'] as $key => $value) {
            if($value->getCateStatus())
                array_push($cateStatus, $value->getCateStatus());
            if($value->getBuildType())
                array_push($cateStatus, $value->getBuildType());
            $buildDoors .= $value->getDoorId().",";
        }
        //查询楼栋下的所有户型
        $findDoors = [];
        $buildDoors = explode(',', trim($buildDoors,','));//拆分成数组
        $findDoors = $this->getDoorsService()->getBuildDoor($buildDoors);

        foreach ($findDoors as $key => $value) {
            if($value['shi'])
                array_push($cateStatus, $value['shi']);
            if($value['ting'])
                array_push($cateStatus, $value['ting']);
            if($value['wei'])
                array_push($cateStatus, $value['wei']);
            if($value['chu'])
                array_push($cateStatus, $value['chu']);
            if($value['cate_type'])
                array_push($cateStatus, $value['cate_type']);
            if($value['cate_status'])
                array_push($cateStatus, $value['cate_status']);
        }
        //查询分类的数据
        $typeWhere = ['id'=>['in'=>$cateStatus]];
        $findCates = $this->getCategoryService()->findBy($typeWhere);
        //获取域名
        $houseUrl = $this->get('service_container')->getParameter('house_url');
        //处理楼栋信息
        foreach ($findBuild['data'] as $key => $value) {
            $buildList[$key]['name'] = $value->getName();
            $buildList[$key]['house_num'] = $value->getHouseNum() ? $value->getHouseNum().'套' : "更新中";
            $buildList[$key]['unit'] = $value->getUnit() ? $value->getUnit()."个单元" : "暂无";
            $buildList[$key]['floor'] = $value->getFloor() ? $value->getFloor()."层" : "暂无";
            $buildList[$key]['hushu'] = $value->getHushu() ? $value->getHushu()."户" : "暂无";
            $buildList[$key]['elevator'] = $value->getElevator() ? $value->getElevator()."个" : '暂无';
            $buildList[$key]['ladder'] = $value->getLadder() ? $value->getLadder() : "暂无";
            $buildList[$key]['kpsj'] = $value->getKpsj()->format('Y-m-d');
            $buildList[$key]['jfsj'] = $value->getJfsj()->format('Y-m-d');
            $buildList[$key]['seat'] = $value->getSeat();
            $buildList[$key]['cate_status'] = "暂无";
            $buildList[$key]['build_type'] = "暂无";
            $doorList = [];

            //匹配户型数据
            if($findDoors){
                foreach ($findDoors as $kd => $vd) {
                    if(in_array($vd['id'] , explode(',', $value->getDoorId()))){
                        $door = [];
                        //匹配分类数据
                        if($findCates['data']){
                            //默认值
                            $door['shi'] = '';
                            $door['ting'] = '';
                            $door['wei'] = '';
                            $door['chu'] = '';
                            $door['id'] = $vd['id'];
                            $door['desc'] = '暂无';
                            $door['cate_type'] = '暂无';
                            $door['cate_status'] = '暂无';
                            $door['dj'] = $vd['dj'] ? $vd['dj']."元/平" : "暂无";
                            $door['totalprice'] = $vd['reference_totalprice'] ? $vd['reference_totalprice']."万/套" : "暂无";
                            $door['mj'] = $vd['mj'] ? $vd['mj']."m²" : "暂无";
                            foreach ($findCates['data'] as $k => $v) {
                                if($value->getCateStatus() == $v->getId()){
                                    $buildList[$key]['cate_status'] = $v->getName();
                                }
                                if($value->getBuildType() == $v->getId()){
                                    $buildList[$key]['build_type'] = $v->getName();
                                }

                                if($vd['shi'] == $v->getId()){
                                    $door['shi'] = $v->getName();
                                }
                                if($vd['ting'] == $v->getId()){
                                    $door['ting'] = $v->getName();
                                } 
                                if($vd['wei'] == $v->getId()){
                                    $door['wei'] = $v->getName();
                                } 
                                if($vd['chu'] == $v->getId()){
                                    $door['chu'] = $v->getName();
                                }
                                if($vd['cate_type'] == $v->getId()){
                                    $door['cate_type'] = $v->getName();
                                } 
                                if($vd['cate_status'] == $v->getId()){
                                    $door['cate_status'] = $v->getName();
                                }
                                $door['name'] = $vd['name'] ? $vd['name'] : "暂无";
                                $door['desc'] = $door['shi'].$door['ting'].$door['wei'].$door['chu'];
                                $door['thumb'] = $vd['thumb'];
                                if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $vd['thumb']) && $vd['thumb'])
                                    $door['thumb'] = $houseUrl."/".$vd['thumb'];
                            }
                        }
                        array_push($doorList, $door);
                    }
                }
            }
            //放入楼栋数组中
            $buildList[$key]['doorList'] = $doorList;
        }
        //查询销售状态的分类
        $typeWhere = ['ename'=>"cate_status",'checked'=>1,'is_delete'=>0];
        $findStatus = $this->getCategoryService()->findBy($typeWhere);
        if($findStatus['data']){
            $statusList = [];
            foreach ($findStatus['data'] as $key => $value) {
                $statusList[$value->getId()] = $value->getName();
            }
        }

        $returnBuild = [
            'dzsp' =>$findDzsp?$houseUrl."/".$findDzsp->getDzsp():"",//电子沙盘图片
            'cateStatus' => $statusList,
            'build' => $buildList
        ];

        return $this->returnSuccess($returnBuild);//以json格式返回数据
    }
    /**
     * @ApiDoc(description="户型列表页-初始化筛选条件--王晓宇")
     *
     * @Rest\QueryParam(name="aid", description="楼盘ID required")
     */
    public function postDoorInitializeAction(Request $request){
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'aid' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('aid', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();//获取请求数据
        //获取楼盘ID
        if (empty($data['aid']))
            throw new BadRequestHttpException('楼盘ID不能为空');
        //定义数组
        $screenDoor = ['screen'=>['0'=>"全部"]];
        //查询销售状态的分类
        $typeWhere = ['ename'=>"cate_status",'checked'=>1,'is_delete'=>0];
        $findStatus = $this->getCategoryService()->findBy($typeWhere);
        if($findStatus['data']){
            foreach ($findStatus['data'] as $key => $value) {
                $screenDoor['screen'][$value->getId()] = $value->getName();
            }
        }
        //查询室的分类
        $shiWhere = ['ename'=>"shi",'checked'=>1,'is_delete'=>0];
        $findShi = $this->getCategoryService()->findBy($shiWhere);

        //获取户型的数量
        $allCount = 0;
        foreach ($findShi['data'] as $key => $value){
            //查询户型数量
            $doorWhere['aid'] = $data['aid'];
            if($value->getId() != 0){
                $doorWhere['shi'] = $value->getId();
            }
            $findDoorCount = $this->getDoorsService()->count($doorWhere);

            $screenDoor['room'][$key]['id'] = $value->getId();
            $screenDoor['room'][$key]['name'] = $value->getName();
            $screenDoor['room'][$key]['count'] = $findDoorCount;
            //统计总的数量
            $allCount += $findDoorCount;
        }
        array_push($screenDoor['room'],['id'=>0,'name'=>"全部",'count'=>$allCount]);

        //返回数据
        return $this->returnSuccess($screenDoor);//以json格式返回数据
    }
    /**
     * @ApiDoc(description="户型列表页-获取户型列表--王晓宇")
     *
     * @Rest\QueryParam(name="aid", description="楼盘ID required") 
     * @Rest\QueryParam(name="sid", description="户型室ID unrequired") 
     * @Rest\QueryParam(name="cstatus", description="销售状态ID unrequired")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="10")
     *
     */
    public function postDoorListAction(Request $request)
    {
        //定义数组
        $doorList = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'aid' => '',
            'sid' => '',
            'cstatus' => '',
            'page' => $this->page,
            'limit' => $this->limit,
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('aid', 'text')
            ->add('sid', 'text')
            ->add('cstatus', 'text')
            ->add('page', 'text')
            ->add('limit', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();//获取请求数据
        $page = $data['page'];
        $limit = $data['limit'];

        //判断
        if (empty($data['aid']))
            throw new BadRequestHttpException('楼盘ID不能为空');

        //查询楼栋的数据
        $where = array('is_delete'=>0);//where条件
        //判断是否有楼盘ID
        if($data['aid'])
            $where['aid'] = $data['aid'];
        $find_build = $this->getBuildsService()->findBy($where);
        if(empty($find_build['data']))
            return $this->returnSuccess([]);//返回空数据

        //从楼栋信息中获取户型的ID串
        $doors = [];//定义户型的ID数组
        foreach ($find_build['data'] as $key => $value) {
            $doors[] = $value->getDoorId();//取出户型的id
        }
        $door_id = trim(implode(',', $doors),",");//取出户型ID
        //查询当前楼盘的城市ID
        $findCity = $this->getHouseService()->findOneBy(['id'=>$data['aid']]);

        //查询户型的数据
        $doorWhere = ['id'=>['in'=>$door_id]];
        if($data['sid'] && $data['sid'] != 0){
            $doorWhere['shi'] = $data['sid'];
        }
        if($data['cstatus'] && $data['cstatus'] != 0){
            $doorWhere['cate_status'] = $data['cstatus'];
        }
        if($findCity->getArea()){
            $doorWhere['area'] = $findCity->getArea();
        }
        $findDoors = $this->getDoorsService()->findBy($doorWhere,[],$limit,$page);

        //分类数据统一查询
        $cateStatus = [];
        foreach ($findDoors['data'] as $key => $value) {
            if($value->getShi())
                array_push($cateStatus, $value->getShi());
            if($value->getTing())
                array_push($cateStatus, $value->getTing());
            if($value->getCateStatus())
                array_push($cateStatus, $value->getCateStatus());
        }
        //查询分类的数据
        $typeWhere = ['id'=>['in'=>$cateStatus]];
        $findCates = $this->getCategoryService()->findBy($typeWhere);

        if(!$findDoors['data'])
            return $this->returnSuccess([]);//返回空数据

        $houseUrl = $this->get('service_container')->getParameter('house_url');
        //处理户型信息
        foreach ($findDoors['data'] as $key => $value) {
            $doorList[$key]['id'] = $value->getId();
            $doorList[$key]['mj'] = $value->getMj();
            $doorList[$key]['totalPrice'] = $value->getReferenceTotalprice();
            $doorList[$key]['thumb'] = $value->getThumb();
            if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $value->getThumb()) && $value->getThumb())
                $doorList[$key]['thumb'] = $houseUrl."/".$value->getThumb();
            $doorList[$key]['cate_status'] = '';
            $doorList[$key]['shi'] = '';
            $doorList[$key]['ting'] = '';
            foreach ($findCates['data'] as $k => $v) {
                if($value->getShi() == $v->getId()){
                    $doorList[$key]['shi'] = $v->getName();
                } 
                if($value->getTing() == $v->getId()){
                    $doorList[$key]['ting'] = $v->getName();
                } 
                if($value->getCateStatus() == $v->getId()){
                    $doorList[$key]['cate_status'] = $v->getName();
                } 
            }
        }

        return $this->returnSuccess($doorList);//以json格式返回数据
    }

    /**
     * @ApiDoc(description="户型详情页-获取户型详情--王晓宇")
     *
     * @Rest\QueryParam(name="hid", description="户型ID required")
     *
     */
    public function postDoorDetailsAction(Request $request)
    {
        //定义数组
        $doorList = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'hid' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('hid', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();//获取请求数据
        //判断是否有户型ID
        if (empty($data['hid']))
            throw new BadRequestHttpException('户型ID不能为空');

        //查询户型的数据
        $findDoor = $this->getDoorsService()->findOneBy(['id'=>$data['hid']]);
        if (!$findDoor)
            return $this->returnSuccess([]);//返回空数据

        $findTenDoor = $this->getDoorsService()->findBy(['aid'=>$findDoor->getAid(),'area'=>$findDoor->getArea()],[],10,1);//查询最新的十条户型数据（推荐户型）
        if(!$findTenDoor['data'])
            $doorList['promoteDoor'] = [];

        $houseUrl  = $this->get('service_container')->getParameter('house_url');
        //查询分类数据
        $cateStatus  = [];//定义分类的数组
        if($findDoor->getShi())
            array_push($cateStatus, $findDoor->getShi());
        if($findDoor->getTing())
            array_push($cateStatus, $findDoor->getTing());
        if($findDoor->getWei())
            array_push($cateStatus, $findDoor->getWei());
        if($findDoor->getChu())
            array_push($cateStatus, $findDoor->getChu());
        if($findDoor->getCateType())
            array_push($cateStatus, $findDoor->getCateType());
        if($findDoor->getCateStatus())
            array_push($cateStatus, $findDoor->getCateStatus());
        if($findTenDoor['data']){
            foreach ($findTenDoor['data'] as $key => $value){
                if($value->getShi())
                    array_push($cateStatus, $value->getShi());
                if($value->getTing())
                    array_push($cateStatus, $value->getTing());
                if($value->getWei())
                    array_push($cateStatus, $value->getWei());
                if($value->getChu())
                    array_push($cateStatus, $value->getChu());
                if($value->getCateStatus())
                    array_push($cateStatus, $value->getCateStatus());
            }
        }

        //查询分类的数据
        $typeWhere = ['id' => ['in' => array_unique($cateStatus)]];
        $findCates = $this->getCategoryService()->findBy($typeWhere);

        //处理详情的数据
        $doorList['detail']['name'] = $findDoor->getName();
        //反json格式化
        $tujis = json_decode($findDoor->getTujis(),true);
        //处理图片无http的数据
        foreach ($tujis as $k => $v){
            if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $v['href']) && $v['href'])
                $tujis[$k]['href'] = $houseUrl."/".$v['href'];
            if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $v['source']) && $v['source'])
                $tujis[$k]['source'] = $houseUrl."/".$v['source'];
        }
        $doorList['detail']['atlass'] = $tujis;
        $doorList['detail']['price'] = $findDoor->getDj() ? $findDoor->getDj()."元/平" : "暂无";
        $doorList['detail']['totalPrice'] = $findDoor->getReferenceTotalprice() ? $findDoor->getReferenceTotalprice()."万" : "暂无";
        $doorList['detail']['mj'] = $findDoor->getMj() ? $findDoor->getMj()."平" : "暂无";

        //默认值
        $doorList['detail']['desc'] = "暂无";
        $doorList['detail']['cateType'] = [];
        $doorList['detail']['cateStatus'] = '暂无';//户型标签
        $doorList['detail']['cateStatus_android']['name'] = "暂无";
        $doorList['detail']['dwelling_space'] = '暂无';
        $doorList['detail']['cateStatus_android']['rgb'] = "暂无";
        $doorList['detail']['supply'] = '暂无';//月供
        $doorList['detail']['layerheight'] = '暂无';//层高
        $doorList['detail']['distribution'] = '暂无';//分布
        $doorList['detail']['inside'] = '暂无';//套内
        $doorList['detail']['browse'] = '';//浏览人数
        $doorList['detail']['updatetime'] = '';//更新时间
        $shi = '';
        $ting = '';
        $wei = '';
        $chu = '';
        if(!empty($findCates['data'])){
            foreach ($findCates['data'] as $k => $v) {
                if($findDoor->getShi() == $v->getId()){
                    $shi = $v->getName();
                }
                if($findDoor->getTing() == $v->getId()){
                    $ting = $v->getName();
                }
                if($findDoor->getWei() == $v->getId()){
                    $wei = $v->getName();
                }
                if($findDoor->getChu() == $v->getId()){
                    $chu = $v->getName();
                }
                $doorList['detail']['desc'] = $shi.$ting.$wei.$chu;
                if($findDoor->getCateType() == $v->getId()){
                    $doorList['detail']['cateType'][0] = $v->getName();
                }
                if($findDoor->getCateStatus() == $v->getId()){
                    $doorList['detail']['cateStatus'] = $v->getName();
                    $doorList['detail']['cateStatus_android']['name'] = $v->getName();//安卓销售状态
                    $doorList['detail']['cateStatus_android']['rgb'] = $this->getValidData(Category::STATUS_RGB_COLOR, $findDoor->getCateStatus());
                }
            }
        }
        $doorList['detail']['toward'] = $findDoor->getHouseToward() ? $findDoor->getHouseToward() : "暂无";
        $doorList['detail']['abstract'] = $findDoor->getAbstract() ? $findDoor->getAbstract() : "暂无";

        //推荐户型数据
        if($findTenDoor['data']){
            foreach ($findTenDoor['data'] as $key => $value){
                $doorList['promoteDoor'][$key]['id'] = $value->getId();
                if(!empty($findCates['data'])){
                    foreach ($findCates['data'] as $k => $v) {
                        if($value->getShi() == $v->getId()){
                            $shi = $v->getName();
                        }
                        if($value->getTing() == $v->getId()){
                            $ting = $v->getName();
                        }
                        if($value->getWei() == $v->getId()){
                            $wei = $v->getName();
                        }
                        if($value->getChu() == $v->getId()){
                            $chu = $v->getName();
                        }
                        $doorList['promoteDoor'][$key]['desc'] = $shi.$ting.$wei.$chu;
                        if($value->getCateStatus() == $v->getId()){
                            $doorList['promoteDoor'][$key]['cate_status_text'] = $v->getName();
                            $doorList['promoteDoor'][$key]['rgb'] = $this->getValidData(Category::STATUS_RGB_COLOR, $value->getCateStatus());
                        }
                    }
                }
                $doorList['promoteDoor'][$key]['mj'] = $value->getMj() ? $value->getMj()."平" : '暂无';
                $doorList['promoteDoor'][$key]['thumb'] = $value->getThumb() ? $value->getThumb() : '';
                if(!preg_match('/(http:\/\/)|(https:\/\/)/i', $value->getThumb()) && $value->getThumb())
                    $doorList['promoteDoor'][$key]['thumb'] = $houseUrl."/".$value->getThumb();

            }
        }

        //返回数据
        return $this->returnSuccess($doorList);//以json格式返回数据
    }

    /** 
     * 根据ID查询属性表中的名称 
     * @param array $id  ID
     * @return array $name 名称 
     */  
    // protected function findCateGory($id)  
    // {  
    //     if($id){
    //         $findcate = $this->getCategoryService()->findOneBy(array('is_delete'=>0,'id'=>$id));
    //         if($findcate){
    //             //返回名称
    //             return $findcate->getName();
    //         }else{
    //             return 0;
    //         }
    //     }else{
    //         return 0;
    //     }
    // }  
    /** 
     * 多维数组转化为一维数组 
     * @param array $array 多维数组 
     * @return array $result_array 一维数组 
     */  
    // protected function arrayMulti2single($array)  
    // {  
    //     //首先定义一个静态数组常量用来保存结果  
    //     static $result_array = array();  
    //     //对多维数组进行循环  
    //     foreach ($array as $value) {  
    //         //判断是否是数组，如果是递归调用方法  
    //         if (is_array($value)) {  
    //             $this->arrayMulti2single($value);  
    //         } else  //如果不是，将结果放入静态数组常量  
    //             $result_array [] = $value;  
    //     }  
    //     //返回结果（静态数组常量）  
    //     return $result_array;  
    // }  

    /**
     * 提供默认广告数据
     *
     */
    public function getAdvertListAction(Request $request)
    {
        //定义数组
        $buildlist = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'aid' => '',
        ], [
            'method' => 'GET',
            'csrf_protection' => false
        ])
            ->add('aid', 'text')
            ->getForm();


        $form->submit($request->query->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();//获取请求数据

        if (!$data['aid'])
            throw new BadRequestHttpException('城市ID不能为空');
        $where['is_delete'] = 0;
        $where['area'] = $data['aid'];
        //根据城市ID查询最新的三个楼盘数据
        $find_build = $this->getHouseService()->findBy($where,array(),3,10);//查询楼盘的数据

        $hotBuild = [];
        foreach ($find_build['data'] as $key => $value) {
            //查询城市
            if($value->getArea()){
                $findRegion = $this->getAreaService()->findOneBy(array('is_delete'=>0,'id'=>$value->getArea()));
                $hotBuild[$key]['area'] = $findRegion->getName();
            }
            $hotBuild[$key]['buildName'] = $value->getName();
            $hotBuild[$key]['address'] = $value->getAddress();
            $hotBuild[$key]['thumb'] = $value->getThumb();
            $hotBuild[$key]['url'] = "http://cms.zhuge.com/xinfang/detail/".$value->getId().".html";
            
        }
        //根据城市ID查询最新的三个楼盘数据
        $find_pingpai = $this->getHouseService()->findBy($where,array(),3,20);//查询楼盘的数据

        $pingBuild = [];
        foreach ($find_pingpai['data'] as $key => $value) {
            //查询城市
            if($value->getArea()){
                $findRegion = $this->getAreaService()->findOneBy(array('is_delete'=>0,'id'=>$value->getArea()));
                $pingBuild[$key]['area'] = $findRegion->getName();
            }
            $pingBuild[$key]['buildName'] = $value->getName();
            $pingBuild[$key]['address'] = $value->getAddress();
            $pingBuild[$key]['thumb'] = $value->getThumb();
            $pingBuild[$key]['url'] = "http://cms.zhuge.com/xinfang/detail/".$value->getId().".html";
            
        }

        //根据城市ID查询最新的三个楼盘数据
        $find_xinfang = $this->getHouseService()->findBy($where,array(),2,30);//查询楼盘的数据

        $xinfangBuild = [];
        foreach ($find_xinfang['data'] as $key => $value) {
            //查询城市
            if($value->getArea()){
                $findRegion = $this->getAreaService()->findOneBy(array('is_delete'=>0,'id'=>$value->getArea()));
                $xinfangBuild[$key]['area'] = $findRegion->getName();
            }
            $xinfangBuild[$key]['buildName'] = $value->getName();
            $xinfangBuild[$key]['address'] = $value->getAddress();
            $xinfangBuild[$key]['thumb'] = $value->getThumb();
            $xinfangBuild[$key]['url'] = "http://cms.zhuge.com/xinfang/detail/".$value->getId().".html";
            $xinfangBuild[$key]['price'] = $value->getDj();
            
        }
        array_push($buildlist, [
            'hotBuild' => $hotBuild,//电子沙盘图片
            'brandBuild' => $pingBuild,//楼盘名称
            'newBuild' => $xinfangBuild
        ]);
    
        return $this->returnSuccess($buildlist);//以json格式返回数据
    }
}