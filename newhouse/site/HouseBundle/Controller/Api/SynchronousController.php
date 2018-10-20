<?php
/**
 * Created by PhpStorm.
 * User: wangxiaoyu
 * Date: 2018/06/01
 * Time: 16:32
 */

namespace HouseBundle\Controller\Api;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\Houses;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use HouseBundle\Form\Api\HouseListQueryType;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SynchronousController extends RestController
{
    private $dateUrl = 'http://dataopen.dapi.zhugefang.test/newhouse/new/data';

    /**
     * @ApiDoc(description="楼盘数据同步date")
     * @Rest\RequestParam(name="city", description="城市id" )
     *
     */
    public function getHouseDateAction(Request $request)
    {
        try {
            //日期
            $time = strtotime("2018-05-10");
            $city_array = ['sh' => 9, 'sz' => 233, 'wh' => 203, 'cd' => 272, 'nj' => 108, 'tj' => 2, 'hz' => 121, 'cq' => 22, 'sy' => 70, 'dl' => 71, 'qd' => 170, 'su' => 112, 'zz' => 186, 'gz' => 231, 'jn' => 169, 'km' => 302, 'cc' => 84, 'xa' => 325, 'heb' => 93, 'cs' => 217, 'wx' => 109, 'sjz' => 36, 'jh' => 127, 'dg' => 247, 'hf' => 132, 'nn' => 252, 'fz' => 149, 'nc' => 158, 'nb' => 122, 'cz' => 111, 'zh' => 234, 'yt' => 174, 'huizhou' => 241, 'zs' => 248, 'lz' => 335, 'xz' => 110, 'yz' => 117, 'ly' => 188, 'wf' => 175, 'xm' => 150, 'nt' => 113, 'yc' => 206, 'jm' => 237, 'guilin' => 254, 'taizhou' => 119, 'taiyuan' => 47, 'sx' => 126, 'qz' => 153, 'gy' => 293, 'bd' => 41, 'ts' => 37, 'weihai' => 178, 'qhd' => 38, 'yinchuan' => 357, 'lf' => 45, 'hhht' => 58, 'fs' => 236, 'jx' => 124, 'wz' => 123, 'xianyang' => 328, 'jiujiang' => 161, 'wlmq' => 362, 'bh' => 256, 'lyg' => 114, 'tz' => 130, 'zhenjiang' => 118, 'huzhou' => 125, 'yancheng' => 116, 'st' => 235, 'hd' => 39, 'wuhu' => 133, 'mianyang' => 277, 'ganzhou' => 164, 'huaian' => 115, 'bt' => 59, 'liuzhou' => 253];
            foreach ($city_array as $ck => $cv) {

                //查询楼盘数据
                $where = [
                    'create_time' => [
                        'gt' => $time
                    ],
                    'area' => $cv,
                    'checked' => 1,
                    'is_delete' => 0
                ];

                $findHouse = $this->getHouseService()->findBy($where, [], 10, 9);
                //请求数据
                $date = [
                    'city' => $ck,
                    'source_name' => 'zhuge',//渠道名称
                    'datas' => [] //楼盘数据
                ];
                $houseDate = [];
                if ($findHouse['data']) {
                    foreach ($findHouse['data'] as $k => $v) {
                        //根据城区ID查询城区名称
                        $region = $this->getAreaService()->findOneBy(['id' => $v->getRegion()]);
                        //根据上去ID查询商圈名称
                        $cateCircle = $this->getCateCircleService()->findOneBy(['id' => $v->getCateCircle()]);
                        //查询交房时间
                        $houseMake = $this->getHouseMakeService()->findOneBy(['aid' => $v->getId()]);
                        //查询相册
                        $inter = $this->interDistribution($v);
                        //查询附属信息
                        $criteria = [
                            'checked' => 1,
                            'aid' => $v->getId(),
                        ];
                        $houseInfo = $this->getHouseInfoService()->findOneBy($criteria);

                        $options = $this->get('service_container')->getParameter('options');
                        // 用到的类型
                        $categoryIds = [
                            $v->getCateType(),  // 物业类型
                            $v->getZxcd(),  // 装修状况
                            $v->getCateStatus(),  // 销售状态
                        ];

                        $categoryInfos = $this->getHouseHandler()->getCategory(['id' => ['in' => $categoryIds]]);

                        // 获取 houseAttr
                        $houseAttrs = $this->getHouseHandler()->getAttrInfo($v);

                        $houseDate = (object)[
                            'data' => (object)[
                                'complex_name' => $v->getName(),
                                'complex_alias' => $v->getAliasName(),
                                'complex_address' => $v->getAddress(),
                                'developer_offer' => $v->getDj() ? (string)$v->getDj() . "元/平" : '',
                                'cityarea_id' => $region ? $region->getName() : '',
                                'cityarea2_id' => $cateCircle ? $cateCircle->getName() : '',
                                'source_url' => 'http://www.newhouse.zhuge.com/',
                                'source_id' => 81, //诸葛找房渠道source_id 81
                                'image_effect' => $inter['image_effect'],//效果图
                                'image_showroom' => $inter['image_showroom'],//样板间
                                'image_live' => $inter['image_live'],//实景
                                'image_supporting' => $inter['image_supporting'],//配套
                                'image_plan' => $inter['image_plan'],//规划
                                'complex_tag' => [$this->getHouseService()->getTslp($v->getTslp())], //楼盘标签
                                'complex_building_type' => $houseInfo ? $this->getHouseInfoService()->getBuildType($houseInfo->getComplexBuildingType()) : '',//建筑类型
                                'complex_loopline' => $this->getValidData($options['hxs'], $v->getHxs()) ? $this->getValidData($options['hxs'], $v->getHxs()) : "", //环线
                                'developer_name' => $v ? $v->getHouseDeveloper() : '', //开发商
                                'sale_status' => $v ? $this->getValidData($categoryInfos, $v->getCateStatus()) : '', //销售状态
                                'preferential_status' => '', //开发商优惠
                                'property_company' => $v ? $v->getWygs() : '',//物业公司
                                'property_costs' => $v ? $v->getWyf() : '',//物业费
                                'property_type' => $v ? $this->getValidData($categoryInfos, $v->getCateType()) : '',//物业类型
                                'hydropower_gas' => $houseInfo ? sprintf('%s,%s,%s',
                                    $houseInfo->getProvideWater(),      // 水
                                    $houseInfo->getProvideElectric(),   // 电
                                    $houseInfo->getProvideGas()         // 气
                                ) : '',//水电燃气
                                'heating_mode' => $houseInfo ? $houseInfo->getProvideHeating() : '',//供暖方式
                                'greening_rate' => $houseInfo ? $this->getValidData($houseAttrs, 'lhl') : '',//绿化率
                                'parking_count' => $houseInfo ? $this->getValidData($houseInfo->getParkingCount()) : '',//车位数量
                                'volume_rate' => $houseAttrs ? $this->getValidData($houseAttrs, 'rjl') : '',//容积率
                                'renovation' => $categoryInfos ? $this->getValidData($categoryInfos, $v->getZxcd()) : '',//装修状况
                                'first_saletime' => $v ? $v->getKpdate()->format('Y-m-d') : '',//最早开盘时间
                                'first_delivertime' => $houseInfo ? $houseInfo->getFirstnewDelivertime()->format('Y-m-d') : '',//最早交房时间
                                'complex_license' => (array)[
                                    (object)[
                                        'license' => $v ? $v->getXkzh() : '',//许可证号
                                        'pubtime' => '',//许可证时间
                                    ]
                                ],//许可证号
                                'lng' => $v ? $v->getLng() : '',//经度
                                'lat' => $v ? $v->getLat() : '',//纬度
                                'main_housetype' => $this->getHouseHandler()->getDoorInfo($v, 1, 2000, ['zlhx' => 1]) ? $this->doorDate($this->getHouseHandler()->getDoorInfo($v, 1, 2000, ['zlhx' => 1])) : [],//主力户型
                                'complex_housetype' => $this->getHouseHandler()->getDoorInfo($v, 1, 2000) ? $this->doorDate($this->getHouseHandler()->getDoorInfo($v, 1, 2000)) : [],//户型
                                'complex_information' => $this->getHouseHandler()->getDynaInfo($v) ? $this->dynaDate($this->getHouseHandler()->getDynaInfo($v)) : [], //动态资讯
                                'complex_comment' => [],//评论
                                'single_building ' => [],//楼栋
                                'hot_line' => $v ? $v->getHotLine() : '',//渠道热线
                                'property_year' => $houseInfo ? $houseInfo->getPropertyYear() : '',//产权年限
                                'take_land_time' => $houseInfo ? $houseInfo->getTakeLandTime()->format('Y-m-d') : '',//拿地时间
                                'complex_desc' => $v ? $v->getContent() : '',//项目介绍
                                'firstnew_saletime' => '',//最新开盘显示时间
                                'salesoffice_address' => $v ? $v->getSldz() : '',//售楼处地址
                                'architectural' => $this->getValidData($options['architectural'], $houseInfo ? $houseInfo->getArchitectural() : ''),//建筑形式
                                'building_totalarea' => $houseAttrs ? $this->getValidData($houseAttrs, 'building_totalarea') : '',//占地面积
                                'building_area' => $houseAttrs ? $this->getValidData($houseAttrs, 'building_area') : '',//建筑面积
                                'building_total' => '',//楼栋总数
                                'house_total' => '',//总户数
                                'property_desc' => '',//物业管理费描述
                                'floor_desc' => '',//楼层状况

                            ],
                            'ctime' => $v->getCreateTime()
                        ];
                        array_push($date['datas'], $houseDate);
                    }
                }
                //请求同步数据接口
                $compareInfo = $this->get('request.handler')->curl_post($this->dateUrl, json_encode($date));
                //解析json
                $jsonReturn = json_decode($compareInfo,true);
                if($jsonReturn['message'] == 'success'){
                    //打印日志
                    $this->get('monolog.logger.zhuge')->error($ck."同步date楼盘数据成功",[
                        'message'=>$compareInfo
                    ]);
                }else{
                    //打印日志
                    $this->get('monolog.logger.zhuge')->error($ck."同步date楼盘数据失败！！！！！！！！！",[
                        'message'=>$compareInfo
                    ]);
                }

            }

        }catch (\Exception $e){
            //打印日志
            $this->get('monolog.logger.zhuge')->error("同步date楼盘数据系统错误.............",[
                'file'=>$e->getFile(),
                'line'=>$e->getLine(),
                'message'=>$e->getMessage()
            ]);
            return $this->returnSuccess(['status'=>'error']);
        }

    }
    //过滤户型的字段
    private function doorDate($zlhxDoor){
        if($zlhxDoor){
            $door  = [];
            foreach ($zlhxDoor as $doorkey => &$doorvalue){
                $door[$doorkey]['housetype_kindname'] = (string)$doorvalue['name'];
                $door[$doorkey]['housetype_tag'] = [];//户型标签
                $door[$doorkey]['housetype_status'] = $doorvalue['cate_status_text'] ? (string)$doorvalue['cate_status_text'] : '';
                $door[$doorkey]['housetype_toward'] = $doorvalue['house_toward'] ? (string)$doorvalue['house_toward'] : '';
                $door[$doorkey]['house_totalarea'] = (string)$doorvalue['mj'];//建筑面积
                $door[$doorkey]['house_area'] = '';//套内面积
                $door[$doorkey]['house_topfloor'] = '';//层高
                $door[$doorkey]['house_layout'] = '';//户型分布
                $door[$doorkey]['reference_price'] = $doorvalue['dj'] ? (string)$doorvalue['dj'] : '';//参考单价
                $door[$doorkey]['reference_total_price'] = (string)$doorvalue['reference_totalprice'] ? $doorvalue['reference_totalprice'] :'';//参考总价
                $door[$doorkey]['reference_down_payment'] = (string)$doorvalue['reference_down_payment'] ? $doorvalue['reference_down_payment'] :'';//参考首付
                $door[$doorkey]['reference_month_payment'] = '';//月供
                $door[$doorkey]['housetype_desc'] = $doorvalue ? $doorvalue['abstract'] : '';//户型描述
                $door[$doorkey]['house_room'] = $doorvalue ? (string)substr($doorvalue['desc'],0,1) : '';//室
                $door[$doorkey]['house_hall'] = $doorvalue ? (string)substr($doorvalue['desc'],4,1) : '';//厅
                $door[$doorkey]['house_toilet'] = $doorvalue ? (string)substr($doorvalue['desc'],8,1) : '';//卫
                $door[$doorkey]['house_kitchen'] = $doorvalue ? (string)substr($doorvalue['desc'],12,1) : '';//厨
            }
            return $door;
        }
        return [];
    }

    //过滤动态资讯的字段都
    private function dynaDate($dyna){
        if($dyna){
            $door  = [];
            $houseUrl = $this->get('service_container')->getParameter('house_url');
            foreach ($dyna as $doorkey => &$doorvalue){
                $door[$doorkey]['building_title'] = (string)$doorvalue['name']; //标题
                $door[$doorkey]['building_content'] = (string)$doorvalue['content'];//内容
                $door[$doorkey]['building_pic'] = (string) $doorvalue['thumb'];//动态图片
                if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $doorvalue['thumb']) && $doorvalue['thumb'])
                    $door[$doorkey]['building_pic'] = $houseUrl . "/" . (string) $doorvalue['thumb'];
                $door[$doorkey]['building_time'] = (string) date('Y-m-d H:i:s',$doorvalue['create_time']);//动态时间
                $door[$doorkey]['building_url'] = ''; //动态来源
            }
            return $door;
        }
        return [];
    }
    /**
     * 根据栏目返回相册数据
     * @param  string $house
     * @return array
     */
    private function interDistribution($house){
        //查询相册
        $inter = $this->getInterAlbumService()->getInterAlbumItem(['aid'=>$house->getId()]);
        if($inter){
            $image['image_effect'] = [];//效果图
            $image['image_showroom'] = [];//样板间
            $image['image_live'] = [];//实景
            $image['image_supporting'] = [];//配置
            $image['image_plan'] = [];//规划
            $houseUrl = $this->get('service_container')->getParameter('house_url');
            foreach ($inter[$house->getId()] as $key => $value){
                if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $value->getThumb()) && $value->getThumb())
                    $value->setThumb($houseUrl . "/" . (string) $value->getThumb());
                if($value->getCateAlbum() == 1){
                    array_push($image['image_effect'],$value->getThumb());
                }elseif ($value->getCateAlbum() == 2){
                    array_push($image['image_showroom'],$value->getThumb());
                }elseif ($value->getCateAlbum() == 3){
                    array_push($image['image_live'],$value->getThumb());
                }elseif ($value->getCateAlbum() == 6){
                    array_push($image['image_supporting'],$value->getThumb());
                }elseif ($value->getCateAlbum() == 7){
                    array_push($image['image_plan'],$value->getThumb());
                }
            }
            return $image;
        }
        return [];

    }


    /**
     * (description="更新楼盘数据接口")
     *
     */
    public function postUpdateHouseAction(Request $request){
        try {
            //接收数据
            //开盘时间 ，均价，审核状态，销售状态
            $request = $request->request->all();
//        $date = [
//            [
//                'name'=>'城建万科城',
//                'dj' => '1000',
//                'cate_status' => '在售',
//                'kpdate' => '2018-09-09',
//                'checked' => '1',
//                'complex_id' => '8012',
//                'city' => 'bj'],
//            [
//                'name'=>'11111111',
//                'dj' => '1000',
//                'cate_status' => '',
//                'kpdate' => '2018-09-09',
//                'checked' => '1',
//                'complex_id' => '8012',
//                'city' => 'bj'
//            ]
//        ];
            //解析数据
            $houseDate = json_decode($request['data'], true);
            if (is_array($houseDate) && $houseDate) {
                foreach ($houseDate as $k => $v) {
                    if ($v['cate_status']) {
                        //根据销售状态查询销售ID
                        $findCates = $this->getCategoryService()->findOneBy(['name' => $v['cate_status'], 'ename' => 'cate_status', 'is_delete' => 0, 'findType' => 1]);
                        if ($findCates) {
                            $saveData['cate_status'] = $findCates['id'];
                        }
                    }

                    if ($v['dj']) {
                        $saveData['dj'] = (int)$v['dj'];
                    }
                    if ($v['kpdate']) {
                        $saveData['kpdate'] = date('Y-m-d', strtotime($v['kpdate']));
                    }
                    if ($v['checked']) {
                        $saveData['checked'] = (int)$v['checked'];
                    }
                    if ($v['complex_id']) {
                        $saveData['complex_id'] = (int)$v['complex_id'];
                    }
                    //根据楼盘名称
                    $findHouse = $this->getHouseService()->findOneBy(['name' => $v['name'], 'findType' => 1]);
                    if ($findHouse) {
                        //更新楼盘数据
                        $updatHouse = $this->getHouseService()->dbalUpdate($saveData, ['id' => $findHouse['id']]);
                        //打印日志
                        $this->get('monolog.logger.zhuge')->error($findHouse['id'] . "更新楼盘数据成功", [
                            'message' => json_encode($saveData)
                        ]);
                    } else {
                        //打印日志
                        $this->get('monolog.logger.zhuge')->error($findHouse['id'] . "更新楼盘数据失败！！！！！！！！！", [
                            'message' => json_encode($saveData)
                        ]);
                        return $this->returnSuccess(['status' => 'error']);
                    }

                }

                return $this->returnSuccess(['status' => 'success']);


            }
        }catch (\Exception $e){
            //打印日志
            $this->get('monolog.logger.zhuge')->error("date更新楼盘数据系统错误.............",[
                'file'=>$e->getFile(),
                'line'=>$e->getLine(),
                'message'=>$e->getMessage()
            ]);
            return $this->returnSuccess(['status'=>'error']);
        }

    }

}