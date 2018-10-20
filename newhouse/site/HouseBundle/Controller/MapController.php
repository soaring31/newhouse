<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月01日
*/
namespace HouseBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
* 地图
* @author house
*/
class MapController extends Controller
{
    /**
    * 实现的index方法
    * house
    */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * houses
    * house
    */
    public function housesAction()
    {

        $location = array();
        $param = array();
        if(isset($_GET['order'])){
            $arr = explode('|', $_GET['order']);
            $param['order'] = $arr[0];
            $param['orderBy'] = $arr[1];
        }
        // $uri = $this->getRequest()->getRequestUri();
        // $nowUri = substr($uri, 0, strpos($uri, '/', strpos($uri, '/', strpos($uri, '/')+1)+1));

        // $msg = array();
        // $msg = $this->get('house.houses')->getTableByAll(1,200,$param);

        //获取全部数据
        $allMsg = $this->get('house.houses')->getTableByAll(1,2000,$param);


        if($this->get('request')->getMethod() == 'POST'){//
            $location['start_x'] = $_POST['start_x'];
            $location['end_x'] = $_POST['end_x'];
            $location['start_y'] = $_POST['start_y'];
            $location['end_y'] = $_POST['end_y'];

            $success = array();


            //遍历所有数据
            for($i=0; $i<count($allMsg['data']); $i++){
                //拿出其map值
                $map = $allMsg['data'][$i]['map'];
                //判断是否为空
                if($allMsg != ''){
                    //按逗号进入切割
                    $arr_map = explode(',', $map);
                    
                    //判断是否出现在当前屏幕中
                    if($location['start_x']<$arr_map[0] && $location['end_x']>$arr_map[0] && $location['start_y']>$arr_map[1] && $location['end_y']<$arr_map[1]){
                        //条件满足，存入变量中
                        $success[] = $allMsg['data'][$i];
                    }
                }
            }

            return new JsonResponse($success);
        }//echo count($allMsg['data']);exit;
        $msg = $this->get('house.houses')->findBy(array(),null,5,isset($_GET['pageIndex'])?$_GET['pageIndex']:1);
        $msg['allData'] = $allMsg['data'];
        $msg['action'] = 'houses';
        $this->parameters = $msg;
        // exit(print_r($allMsg));

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * sale
    * house
    */
    public function saleAction()
    {
        $param = array();
        $location = array();
        if(isset($_GET['order'])){
            $arr = explode('|', $_GET['order']);
            $param['order'] = $arr[0];
            $param['orderBy'] = $arr[1];
        }
        // $uri = $this->getRequest()->getRequestUri();
        // $nowUri = substr($uri, 0, strpos($uri, '/', strpos($uri, '/', strpos($uri, '/')+1)+1));

        // $msg = array();
        // $msg = $this->get('house.houses')->getTableByAll(1,200,$param);

        //获取全部数据
        $allMsg = $this->get('house.sale')->getTableByAll(1,2000,$param);


        if($this->get('request')->getMethod() == 'POST'){//
            $location['start_x'] = $_POST['start_x'];
            $location['end_x'] = $_POST['end_x'];
            $location['start_y'] = $_POST['start_y'];
            $location['end_y'] = $_POST['end_y'];

            $success = array();


            //遍历所有数据
            for($i=0; $i<count($allMsg['data']); $i++){
                //拿出其map值
                $map = $allMsg['data'][$i]['map'];
                //判断是否为空
                if($allMsg != ''){
                    //按逗号进入切割
                    $arr_map = explode(',', $map);
                    
                    //判断是否出现在当前屏幕中
                    if($location['start_x']<$arr_map[0] && $location['end_x']>$arr_map[0] && $location['start_y']>$arr_map[1] && $location['end_y']<$arr_map[1]){
                        //条件满足，存入变量中
                        $success[] = $allMsg['data'][$i];
                    }
                }
            }

            return new JsonResponse($success);
        }//echo count($allMsg['data']);exit;
        $msg = $this->get('house.sale')->findBy(array(),null,5,isset($_GET['pageIndex'])?$_GET['pageIndex']:1);
        $msg['allData'] = $allMsg['data'];
        $msg['action'] = 'sale';
        $this->parameters = $msg;

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * rent
    * house
    */
    public function rentAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的list方法
    * house
    */
    public function listAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 获取区域数据
    * house
    */
    public function areaAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 拾取器
    * house
    */
    public function pickerAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 查看地图
    * house
    */
    public function pointerAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 地图楼房信息
    * house
    */
    public function mapinfoAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 定位
    * house
    */
    public function locationAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 详情
    * house
    */
    public function detailblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 过滤app
    * house
    */
    public function filterblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 导航
    * house
    */
    public function navblockAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}