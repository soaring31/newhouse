<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月07日
*/
namespace HouseBundle\Controller;

/**
* 微信公众号管理
* @author house
*/
class MwxmanageController extends Controller
{
    /**
    * 添加公共号
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

            unset($data['csrf_token']);

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.wxusers')->update($id, $data);
                }
            }else
                $info = $this->get('db.wxusers')->add($data);

            //菜单方案
            if ($data['wxplan'])
            {   
                //获取方案
                $result = $this->get('db.wxplanmenus')->findBy(array('findType' => 1,'aid' => $data['wxplan']));
                
                foreach ($result['data'] as $d)
                {
                    $datas = array();
                    $datas['appid'] = isset($data['appid'])?$data['appid']:'';
                    $datas['kno'] = isset($d['kno'])?$d['kno']:0;
                    $datas['title'] = isset($d['title'])?$d['title']:'';
                    $datas['type'] = isset($d['type'])?$d['type']:'';
                    $datas['keyword'] = isset($d['keyword'])?$d['keyword']:'';

                    $this->get('db.wxmenus')->add($datas, null, false);
                }
            }

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
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
                $this->get('db.wxusers')->delete($id);
            }
        }
        return $this->success('操作成功');
    }
}