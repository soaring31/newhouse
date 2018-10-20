<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月8日
*/
namespace CoreBundle\Services\Square;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 取范围坐标
 * @author Admina
 *
 */
class Square extends ServiceBase 
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

//     public function findround()
//     {
//         $arr = array();
//         $user = $this->get('core.common')->getUser();
    
//         $uid = is_object($user)?$user->getId():0;
    
//         if($uid>0)
//         {
//             $re=mysql_query("select `lat`,`long` from pre_common_member where uid='$uid'")or die(mysql_error());
//             $row=mysql_fetch_assoc($re);
//             if(!empty($row[lat]) and !empty($row[long]))
//             {
//                 $re0=mysql_query("select `username`,`lat`,`long` from pre_common_member where uid!='$uid'");
//                 while($row0=mysql_fetch_assoc($re0))
//                 {
//                     $distance = self::getDistanceBetweenPointsNew($row['lat'], $row['long'], $row0['lat'], $row0['long']);
//                     $row0[meter]=$distance[meters];
//                     $arr[]=$row0;
//                 }
//                 $arr = array_sort($arr,'meter');
//                 $arr = array_slice($arr,0,10);
//                 $data = json_encode($arr);
//                 $s = mysql_errno();
//                 //echo $data;
//                 dump("{\\"s\\":$s,\\"data\\":$data}");
//                 die();
//             }
//         }
//     }

    /**
     * 根据一个给定经纬度的点，进行附近地点查询
     * 获取周围坐标
     * @param double $lng
     * @param double $lat
     * @param real $distance
     * @return multitype:multitype:number
     */
    public function returnSquarePoint($lng, $lat,$distance = 0.5)
    {
        $earthRadius = 6378138;
        $dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);
        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }

    /**
     * 计算两个坐标的直线距离    
     * @param double $lat1
     * @param double $lng1
     * @param double $lat2
     * @param double $lng2
     * @return number
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6378138; //近似地球半径米
        // 转换为弧度
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }

    /**
     * 比较两个地点的距离
     * @param double $latitude1
     * @param double $longitude1
     * @param double $latitude2
     * @param double $longitude2
     * @return multitype:
     */
    function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }

    /**
     * 二维数组按某个key排序
     * @param array $arr
     * @param string $keys
     * @param string $type
     * @return multitype:unknown
     */
    function array_sort(array $arr, $keys, $type='asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k=>$v){
            $keysvalue[$k] = $v[$keys];
        }
        if($type == 'asc'){
            asort($keysvalue);
        }else{
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k=>$v){
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }

    /**
     * 这是一个非常有用的距离计算函数，利用纬度和经度计算从 A 地点到 B 地点的距离。该函数可以返回英里，公里，海里三种单位类型的距离。
     * 用法：
     * distance(32.9697, -96.80322, 29.46786, -98.53506, "k")." kilometers"; 
     * @param double $lat1
     * @param double $lon1
     * @param double $lat2
     * @param double $lon2
     * @param string $unit
     * @return number
     */
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
    
        switch($unit)
        {
            case 'K':
                return ($miles * 1.609344);
                break;
            case 'N':
                return ($miles * 0.8684);
                break;
            default:
                return $miles;
                break;
        }
    }
}