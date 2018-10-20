<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月22日
*/
namespace HouseBundle\Controller;

/**
* 定时任务列表
* @author house
*/
class McronmanageController extends Controller
{



    /**
    * 定时任务编辑
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
                    $this->get('house.cron')->update($id, $data);
                }
            }else
                $this->get('house.cron')->add($data);

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
                $this->get('house.cron')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    public function textAction()
    {
        @set_time_limit(0);
        @ignore_user_abort(TRUE);
        $interval = 1; // 每1个小时执行一次
        do {
            if(!$this->get('core.timers')->cron_switch()) break; //开关的作用
            if (!$interval) break; // 如果循环的间隔为零，则停止
            sleep($interval);
            $this->get('core.timers')->run();

        } while (true);

        return $this->success('操作成功');
    }

}