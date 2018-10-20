<?php

namespace CoreBundle\Services\Timers;


use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Task extends ServiceBase
{
    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    // 付费定时刷新
    public function autoflush($cron)
    {
        $result = $this->get($cron['model'])->findOneBy(array('id'=>$cron['mid']), array(), false);
        $this->get($cron['model'])->update($cron['mid'], array('refreshdate'=>time()), null, false);
    }

    public function users()
    {
        $condition = array();
        $condition['status']['lt'] = 4;
        $result = $this->get('house.recommend')->findBy($condition);

        $data = array();
        $data['status'] = 5;
        foreach($result['data'] as  $val)
        {
            //这里的3天定时 后期动态获取设置天数
            $beign_time =strtotime('+3 days', strtotime( date('Y-m-d H:i:s', $val->getCreatetime()) ) );

            if(time()-$beign_time>0) {
                //统计无效用户个数
                $map = array();
                $map['unnum'] = $info1->getUnnum() + 1;
                $this->get('db.users')->dbalUpdate($map,array('id' => $val->getId()));

                //修改无效状态值
                $this->get('db.users')->dbalUpdate($data, array('id' => $val->getId()));

            }
        }
    }
}
