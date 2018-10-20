<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Controller;
        
/**
* 问答答案管理
* @author house
*/
class ManswermanageController extends Controller
{
        


    /**
    * 问答答案编辑
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
            $data = $this->get('request')->request->all();
            //设置最佳答案有可能会修改到他人的数据
            if($ids)
            {
                if(isset($data['isanswer'])&&$data['isanswer']=='1'&&isset($data['ask']))
                {
                    $askId = $data['ask'];
                    $askData = array('solved' => '1');
                    $this->get('house.ask')->update( $askId, $askData, null, false);
                }
                
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('house.answer')->setNoCheck(true);
                    $this->get('house.answer')->update($id, $data);
                }
            }else
                $this->get('house.answer')->add($data);
        
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
                $this->get('house.answer')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}