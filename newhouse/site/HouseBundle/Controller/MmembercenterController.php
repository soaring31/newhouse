<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月18日
*/
namespace HouseBundle\Controller;
        
/**
* 会员中心
* @author house
*/
class MmembercenterController extends Controller
{
        


    /**
    * 会员中心
    * house
    */
    public function mmemberparamsAction()
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
            $data = $this->get('request')->request->all();
            $data['ename'] = $this->get('request')->get('ename','');
            if(isset($data['ename']))
            {
                $batchAddData = array();
                $batchUpData = array();
                foreach($data as $key => $item)
                {
                    if(empty($key))
                        continue;
                    $info = $this->get('db.mconfig')->findOneBy(array('ename' => $data['ename'], 'name' => $key));

                    if($info){
                        $info->setValue(is_array($item)?end($item):$item);
                        $batchUpData[] = $info;
                    }else{
                        $batchAddData[] = array('name' => $key,'value' => is_array($item)?end($item):$item, 'ename' => $data['ename']);
                    }
                }

                if($batchAddData)
                    $this->get('db.mconfig')->batchadd($batchAddData);
                
                if($batchUpData)
                    $this->get('db.mconfig')->batchupdate($batchUpData);
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
                $this->get('db.mconfig')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 会员中心注释
    * house
    */
    public function mmemberguidesAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}