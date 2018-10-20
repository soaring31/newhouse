<?php
/**
 * Created by PhpStorm.
 * User: taolin
 * Date: 2018/4/13
 * Time: 22:10
 */

namespace HouseBundle\Controller\Api\V1;


use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class TrendController extends RestController
{
    /**
     * @ApiDoc(description="价格走势")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\w+")
     * @Rest\QueryParam(name="area", description="城市id", requirements="\d+")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postTrendAction()
    {
        $city = $this->get('request')->get('city', '');
        $area = $this->get('request')->get('area', '');
        //获取api二手房价格走势
        $api_url = $this->get('service_container')->getParameter('api_url');
        $sell_house_price_url = $api_url . "/API/Home/actualhouseprcie/addon/V3_1_3";
        $data = array(
            "city" => $city,
        );
        $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url, $data);
        $sell_data = json_decode($sell_house_price_data, true);
        $sell_house_price = [];
        $return_arr = [];

        if ($sell_data['code'] == 200) {
            if (!empty($sell_data['data']['tagout_done_prcie']['tagout_price'])) {
                $sell_house_price['average_price'] = $sell_data['data']['average_price'];//均价
                $sell_house_price['percentage'] = $sell_data['data']['percentage'];//环比上月
                $sell_house_price['updatetime'] = $sell_data['data']['updatetime'];//更新日期
                $tagout_price_arr = $sell_data['data']['tagout_done_prcie']['tagout_price'];
                $tagout_price = [];
                foreach($tagout_price_arr as $key => $value){
                    if($key != 0){
                        $tagout_price[] = $value;
                    }
                    $arr_city_one = $value['city'];
                }
                if(!empty($tagout_price)){
                    $tagout_price[] = array(
                        '_id'=>'5ae37333448a832b47b33069',
                        'date'=>date("Ym",time()),
                        'city'=>$arr_city_one,
                        'price'=>$sell_data['data']['average_price'],
                        'update'=>date("Ym",time())
                    );
                }

                $sell_house_price['tagout_price'] = $tagout_price;//价格列表
            } else {
                $sell_house_price['average_price'] = 0;//均价
                $sell_house_price['percentage'] = '0%';//环比上月
                $sell_house_price['updatetime'] = 0;//更新日期
                $sell_house_price['tagout_price'] = array();//价格列表
            }
        }
        //在售房源
        $sell_house_num_url = $api_url . "/API/Home/gethousechange/addon/V3_1_3";
        $sell_data = array(
            "city" => $city,
        );
        $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_num_url, $sell_data);
        $sell_house_data = json_decode($sell_house_price_data, true);
        if ($sell_house_data['code'] == 200) {
            if (!empty($sell_house_data['data']['all_house'])) {
                $sell_house_price['all_house_count'] = $sell_house_data['data']['all_house']['count_house'];//在售
                $sell_house_price['new_count'] = $sell_house_data['data']['new_count'];//新上
            } else {
                $sell_house_price['all_house_count'] = 0;
                $sell_house_price['new_count'] = 0;//新上
            }
        }
        //新上
        if (!empty($sell_house_price))
            $return_arr['sell_house_price'] = $sell_house_price;
        else
            $return_arr['sell_house_price'] = [];

        //新房
        $currentTime = time();
        $cyear = floor(date("Y", $currentTime));
        $cMonth = floor(date("m", $currentTime));

        for ($i = 0; $i < 12; $i++) {
            $nMonth = $cMonth - $i;
            $cyear = $nMonth == 0 ? ($cyear - 1) : $cyear;
            $nMonth = $nMonth <= 0 ? 12 + $nMonth : $nMonth;
            $date = $cyear . "-" . $nMonth . "-1";
            $firstday = date('Y-m-01', strtotime($date));
            $lastday = date('Y-m-t', strtotime($date));
            $nMonth = $nMonth > 9 ? $nMonth : '0' . $nMonth;
            $time_arr[] = array(
                "start" => $firstday,
                "end" => $lastday,
                "date" => $cyear . $nMonth
            );

        }
            $new_house_price = [];
        foreach ($time_arr as $key => $value) {
            //12个月的均价
            $data = $this->getPricesService()->getPrice($value['start'], $value['end'], $area);
            if (!empty($data[0])) {
                $new_house_price['tagout_price'][] = array(
                    "date" => $value['date'],
                    "price" => floor($data[0]['pirce_avg']),
                );
            }else{
                $new_house_price['tagout_price'][] = array(
                    "date" => $value['date'],
                    "price" => 0,
                );
            }
        }
        //环比上月价格
        if (!$now_price = $this->getPricesService()->findOneBy(array('area' => $area), array('create_time' => 'desc')))
            $now_price = 1;
        else
            $now_price = $now_price->getPrice();

        $now_price = empty($new_house_price['tagout_price'][0]['price'])?0:$new_house_price['tagout_price'][0]['price'];
        $up_price = empty($new_house_price['tagout_price'][1]['price'])?0:$new_house_price['tagout_price'][1]['price'];
        $percentage = number_format(((($now_price - $up_price) / $now_price) * 100),2);
        $new_house_price['percentage'] = $percentage;
        //在售新楼
        $count_where = array(
            "cate_status" => 309,
            "area" => $area,
            "is_delete" => 0
        );
        $sell_house_count = $this->getHouseService()->count($count_where);
        $new_house_price['sell_house_count'] = $sell_house_count;
        //今日新增
        $count_where = array(
            "create_time" => array("eq"),
            "area" => $area,
            "is_delete" => 0
        );
        $start = strtotime(date('Y-m-01', time()));
        $end = strtotime(date('Y-m-t', time()));
        $month_house_count = $this->getHouseService()->getMonthHouseCount($start, $end, $area);
        $new_house_price['month_house_count'] = (int)$month_house_count[0]['house_count'];

        //排序
        $tagout_price = array();
        $tagout_price = array_reverse($new_house_price['tagout_price']);
        $new_house_price['tagout_price'] = $tagout_price;

        if (!empty($new_house_price))
            $return_arr['new_house_price'] = $new_house_price;
        else
            $return_arr['new_house_price'] = [];

        return $this->returnSuccess($return_arr);

    }
}