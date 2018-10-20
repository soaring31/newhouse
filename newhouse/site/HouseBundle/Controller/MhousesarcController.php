<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月06日
*/
namespace HouseBundle\Controller;
        
/**
* 楼盘文档管理
* @author house
*/
class MhousesarcController extends Controller
{
        


    /**
    * 楼盘文档编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');            
            $fromid = $this->get('request')->get('fromid', '');
            $data = $this->get('request')->request->all();
        
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('house.houses_arc')->update($id, $data);
                }              
            }elseif($fromid){
                $fromid = explode(',', $fromid);           
                $info = array();

                //注入区域字段
                if(!isset($data['area']))
                    $data['area'] = $this->get('core.area')->getArea();

                foreach($fromid as $v)
                {
                    $map = array();
                    $map['fromid'] = $v;
                    $map['cate_pushs'] = $this->get('request')->get('cate_pushs', '');
                    $map['models'] = $this->get('request')->get('models', '');

                    if(isset($data['aid']))
                        $map['aid'] = $this->get('request')->get('aid', '');

                    $data['fromid'] = $v;

                    $count = $this->get('house.houses_arc')->count($map);

                    if($count>0)
                        continue;

                    $info[] = $data;
                }

                if($info)
                    $this->get('house.houses_arc')->batchadd($info);
            }
            
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }            
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');
        
        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('house.houses_arc')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 楼盘文档加载信息
    * house
    */
    public function loadarcsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}