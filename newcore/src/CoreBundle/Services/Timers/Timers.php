<?php
namespace CoreBundle\Services\Timers;


use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * 定时器
 *
 */
class Timers extends ServiceBase
{

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        $map = array();
        $map['findType'] = 1;
        $map['nextrun'] = time();
        $map['count']['gt'] = 0;
        $crons = $this->get('house.cron')->findBy($map);
        if (isset($crons['data']))
        {
            foreach ($crons['data'] as $cron)
            {
                // 执行次数大于0
                if ($cron['count']>0)
                {
                    // 执行服务
                    $this->get('core.task')->{$cron['service_name']}($cron);
            
                    // 次数 -1
                    $data = array();
                    $data['count'] = $cron['count']-1;
                    $data['nextrun'] = $this->setNextrun($cron);
                    
                    // 设置下一次执行的时间戳
                    $this->get('house.cron')->update($cron['id'], $data, null, false);
                }
            }
        }
    }
    
    public function setNextrun(array $cron)
    {
        $time = 0;
        switch ((int)$cron['type'])
        {
            //固定一个或者多个时间点，并且每天只刷新一次
            case 1:
                $hour = explode(',', $cron['hour']);
                sort($hour);
                $nowHour = date('G.i');
        
                //当天下次执行的时间戳
                if (end($hour) > $nowHour)
                {
                    foreach ($hour as $v)
                    {
                        if ($v > $nowHour)
                        {
                            if (!(substr($v, -2) == '.0') || !(substr($v, -2) == '.00'))
                                $v = $v.'.0';
                            $time = strtotime($v);
                        }
                    }
                } else {
                    // 次天执行第一次的时间戳
                    if (!(substr($hour[0], -2) == '.0') || !(substr($hour[0], -2) == '.00'))
                        $hour[0] = $hour[0].'.0';
                    $time = strtotime($hour[0]) + 86400;
                }
                break;
            //固定每间隔几个小时刷新一次，每天次数不限
            case 2:
                $time = time() + $cron['hour']*3600;
                break;
        }
        return $time;
    }
    
    public function runback()
    {
        $data = array();
        $map = array();
        $next = array();
//        $map['nextrun']['orX'][]['nextrun']['gte'] = time();
//        $map['nextrun']['orX'][]['day']['eq'] = -1;
        $result = $this->get('house.cron')->findBy($map);

        if(isset($result['data']))
        {
            foreach($result['data'] as $cron)
            {
                if($cron->getNextrun()>=time())
                {

                    $fileClass = trim($cron->getServiceName());
                    $data['id'] = $cron->getId();
                    $data['weekday'] = $cron->getWeekday();
                    $data['day'] = $cron->getDay();
                    $data['minute'] = explode(",", $cron->getMinute());
                    $data['hour'] = explode(",", $cron->getHour());

                    $hournow = gmdate('H', time() + 8 * 3600);
                    $minutenow = gmdate('i', time() + 8 * 3600);

                    if (max($data['hour']) >= $hournow)
                    {
                        foreach ($data['hour'] as $hour)
                        {
                            if ($hour > $hournow) {
                                $data['hour'] = $hour;
                                break;
                            } elseif ($hour == $hournow) {
                                if (max($data['minute']) > $minutenow) {
                                    $data['hour'] = $hour;
                                    break;
                                } else {
                                    if ($hour == max($data['hour']))
                                        $data['hour'] = min($data['hour']);
                                    continue;
                                }
                            }
                        }
                    } else {
                        $data['hour'] = min($data['hour']);
                    }

                    //执行服务
                    $this->get('core.task')->{$fileClass}();
                    if ($data['weekday'] && $data['day'])
                    {
                        //设置下次执行时间
                        $next['nextrun'] = self::setnextime($data);  
                        $this->get('house.cron')->dbalUpdate($next, array('id' => (int)$cron->getId()));                   
                    } else
                        $this->get('house.cron')->dbalDelete(array('id' => (int)$cron->getId()));
                }
            }
        }
        return true;
    }


    /**
     * 设置下一次执行时间
     *
     */
    public function setnextime($cron)
    {
        if(empty($cron))
            return false;
        //, $hournow, $minutenow
        list($yearnow, $monthnow, $daynow, $weekdaynow) = explode('-', gmdate('Y-m-d-w-H-i', time() + 8 * 3600));
        if($cron['weekday'] == -1)
        {
            if($cron['day'] == -1)
            {
                $firstday = $daynow;
                $secondday = $daynow + 1;
            } else {
                $firstday = $cron['day'];
                $secondday = $cron['day'] + gmdate('t', time() + 8 * 3600);
            }
        } else {
            $firstday = $daynow + ($cron['weekday'] - $weekdaynow);
            $secondday = $firstday + 7;
        }

        if(empty($cron['weekday']) &&empty( $cron['day'] ))
        {
            $firstday = intval($daynow)+1;
            $cron['day'] = intval($daynow) +1 ;
            $cron['weekday'] =$weekdaynow + 1;
        }

        if($firstday < $daynow)
            $firstday = $secondday;

        if($firstday == $daynow)
        {
            $todaytime = self::todaynextrun($cron);
            if($todaytime['hour'] == -1 && $todaytime['minute'] == -1)
            {
                $cron['day'] = $secondday;
                $nexttime = self::todaynextrun($cron, 0, -1);
                $cron['hour'] = $nexttime['hour'];
                $cron['minute'] = $nexttime['minute'];
            } else {
                $cron['day'] = $firstday;
                $cron['hour'] = $todaytime['hour'];
                $cron['minute'] = $todaytime['minute'];
            }

        } else {
            $cron['day'] = $firstday;
            $nexttime = self::todaynextrun($cron, 0, -1);
            $cron['hour'] = $nexttime['hour'];
            $cron['minute'] = $nexttime['minute'];
        }
        $nextrun = @gmmktime($cron['hour'], $cron['minute'] > 0 ? $cron['minute'] : 0, 0, $monthnow, $cron['day'], $yearnow) - 8 * 3600;

        return $nextrun;
    }
    /**
     * 设置第一次执行时间
     *
     */
    public function firsttime($data)
    {
        $hournow = gmdate('H', time() + 8 * 3600);
        $minutenow = gmdate('i', time() + 8 * 3600);
        $data['minute'] = explode(",", $data['minute']);
        $data['hour'] = explode(",",  $data['hour']);
        if (max($data['hour']) >= $hournow)
        {
            foreach ($data['hour'] as $hour)
            {
                if ($hour > $hournow)
                {
                    $data['hour'] = $hour;
                    break;
                } elseif ($hour == $hournow) {
                    if (max($data['minute']) > $minutenow) {
                        $data['hour'] = $hour;
                        break;
                    } else {
                        if ($hour == max($data['hour']))
                            $data['hour'] = min($data['hour']);

                        continue;
                    }
                }
            }
        } else {
            $data['hour'] = min($data['hour']);
        }
        return  self::setnextime($data);
    }

    public function todaynextrun($cron, $hour = -2, $minute = -2)
    {
        $hour = $hour == -2 ? gmdate('H', time() + 8 * 3600) : $hour;
        $minute = $minute == -2 ? gmdate('i', time() + 8 * 3600) : $minute;
        $nexttime = array();
        if($cron['hour'] == -1 && !$cron['minute'])
        {
            $nexttime['hour'] = $hour;
            $nexttime['minute'] = $minute + 1;
        } elseif($cron['hour'] == -1 && $cron['minute'] != '') {
            $nexttime['hour'] = $hour;
            if(($nextminute = self::nextminute($cron['minute'], $minute)) === false)
            {
                ++$nexttime['hour'];
                $nextminute = $cron['minute'][0];
            }
            $nexttime['minute'] = $nextminute;
        } elseif($cron['hour'] != -1 && $cron['minute'] == '') {
            if($cron['hour'] < $hour)
            {
                $nexttime['hour'] = $nexttime['minute'] = -1;
            } elseif($cron['hour'] == $hour) {
                $nexttime['hour'] = $cron['hour'];
                $nexttime['minute'] = $minute + 1;
            } else {
                $nexttime['hour'] = $cron['hour'];
                $nexttime['minute'] = 0;
            }
        } elseif($cron['hour'] != -1 && $cron['minute'] != '') {
            $nextminute = self::nextminute($cron['minute'], $minute);
            if($cron['hour'] < $hour || ($cron['hour'] == $hour && $nextminute === false))
            {
                $nexttime['hour'] = -1;
                $nexttime['minute'] = -1;
            } elseif($cron['hour'] > $hour) {
                $nexttime['hour'] = $cron['hour'];
                $nexttime['minute'] = min($cron['minute']);
            }else{
                #var_export($nextminute);
                $nexttime['hour'] = $cron['hour'];
                $nexttime['minute'] = $nextminute;
            }
        }

        return $nexttime;
    }

    /**
     * 下一次执行分钟
     *
     */
    public function nextminute($nextminutes, $minutenow)
    {
        foreach($nextminutes as $nextminute)
        {
            if($nextminute > $minutenow)
                return $nextminute;
        }
        return false;
    }

    public function cron_switch()
    {
        return true;
    }
}
