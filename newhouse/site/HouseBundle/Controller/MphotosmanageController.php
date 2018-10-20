<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月14日
*/
namespace HouseBundle\Controller;
        
/**
* 图库管理
* @author house
*/
class MphotosmanageController extends Controller
{
        

    /**
    * 编辑
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
            $id = $this->get('request')->get('id', 0);
            
            $ids = $this->get('request')->get('id', '');
            
            $_form_id = $this->get('request')->get('_form_id',0);
            
            $data = $this->get('request')->request->all();
            
            if($ids)
            {                
                $ids = explode(',', $ids);
                $infos = array();
                foreach($ids as $id)
                {
                    if($_form_id>0)
                    {
                        $this->get('house.photos')->update($id, $data);
                    }else{
                        $info = $this->get('house.photos')->findOneBy(array('id'=>$id));
                        $info = $this->get('house.photos')->dataToEntity($data, $info);
                        $infos[] = $info;
                    }
                }
                
                if($infos)
                    $this->get('house.photos')->batchupdate($infos);
                
                return $this->success('操作成功');
            }else
                $this->get('house.photos')->add($data);
        
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
        $ids = $this->get('request')->get('id', 0);
        $ids = $ids?explode(',', $ids):array();
        
        foreach($ids as $id)
        {
            $this->get('house.photos')->delete($id);
        }

        return $this->success('操作成功');
    }
}