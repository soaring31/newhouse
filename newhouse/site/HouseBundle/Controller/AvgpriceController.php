<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月12日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class AvgpriceController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }
    public function cronAction()
    {
        $this->get('core.timers')->run();

        $this->parameters['data'] = '执行计划任务成功';
        return $this->render($this->getBundleName(), $this->parameters);
    }
    /**
     * 实现的index方法
     * house
     */
    public function indexAction()
    {
        $tables = array();  
        $tables['house.houses'] = array('price'=>'dj','category'=>195,'map'=>array('checked'=>1));
        $tables['house.sale'] = array('price'=>'dj','category'=>197);
        $tables['house.sale']['map']['valid']['gt'] = time();
        $tables['house.sale']['map']['checked'] = 1;
        $tables['house.rent'] = array('price'=>'czj','category'=>198);
        $tables['house.rent']['map']['valid']['gt'] = time();
        $tables['house.rent']['map']['checked'] = 1;

        $result = $this->get('db.auth_group_area')->findBy(array('findType'=>1));
        $area = '';
        foreach ($result['data'] as $k=>$v)
        {
            $area .= $v['rulesarea'].',';
        }
        $map['id']['in'] = rtrim($area,',');
        $map['findType'] = 1;
        
        $datas = $this->get('db.area')->findBy($map);

        foreach ($tables as $tablek=>$tablev)
        {
            foreach ($datas['data'] as $data)
            {
                // 分站统计用的
                $count = $price = 0;
                $addData = array();
                
                // $result1 同一区域的所有数据
                $result1 = $this->get('serializer')->normalize($this->get('db.area')->getNodeById($data['id']));

                foreach ($result1['data'] as $data1)
                {
                    // 区域统计用的
                    $count2 = $price2 = 0;

                    // $result2 根据同一区域的id去house 表里找出对应的数据                   array_merge($map, $params)
                    $result2 = $this->get($tablek)->findBy(array_merge($tablev['map'],array('region'=>$data1['id'],'findType'=>1)));

                    foreach ($result2['data'] as $data2)
                    {
                        $price += $data2[$tablev['price']];
                        $price2 += $data2[$tablev['price']];
                        $count++;
                        $count2++;
                    }
                    //统计区域下的平均数
                    if ($count2)
                    {
                        $addData2 = array();
                        $addData2['months'] = date('Y-m-d',strtotime('now'));
                        $addData2['area'] = $data1['id'];
                        //平均数
                        $addData2['price'] = (int)($price2/$count2);
                        $addData2['category'] = $tablev['category'];

                        $this->get('house.prices')->add($addData2, null, false);
                    }
                }
                
                // 统计分站下的平均数
                if($count)
                {
                    $addData['months'] = date('Y-m-d',strtotime('now'));
                    $addData['area'] = $data['id'];
                    //平均数
                    $addData['price'] = (int)($price/$count);
                    $addData['category'] = $tablev['category'];

                    $this->get('house.prices')->add($addData, null, false);
                }
            }
        }
        
        return $this->success('统计成功' ,'', true);
    }
}