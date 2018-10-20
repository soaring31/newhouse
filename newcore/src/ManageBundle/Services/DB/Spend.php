<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月22日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 消费表
* 
*/
class Spend extends AbstractServiceManager
{
    protected $table = 'Spend';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    /**
     * 积分记录
     * @param array $totalIntegral 总额
     * @param unknown $tableName     表名
     */
    public function recordIntegral(array $totalIntegral,$tableName)
    {
        $user = parent::getUser();
        foreach ($totalIntegral as $arr)
        {
            $data = array();
            $data['name'] = $arr['title'];
            $data['uid'] = method_exists($user, 'getId')?$user->getId():0;
            $data['touid'] = $data['uid'];
            //类型
            switch ($arr['name'])
            {
                case 'jifen':
                    $data['consume'] = 7;
                    break;
                case 'jingjixinyong':
                    $data['consume'] = 8;
            }
            //总额   未消费前的金额
            $data['cmoney'] = $arr['value'];
            //余额   消费后的金额
            $map = array();
            $map['mid'] = $user->getUserinfo()->getId();
            $map['title'] = $data['name'];
            $data['bmoney'] = self::get('db.user_attr')->findOneBy($map)->getValue();
            $data['services'] = self::get('db.models')->findOneBy(array('name'=>$tableName))->getServicename();
            $data['aid'] = $user->getId();
            //消费金额
            $data['amount'] = $data['bmoney'] - $data['cmoney'];

            self::add($data, null, false);
        }
    }
    
    /**
     * 兑换(根据配置方案)
     */
    public function exchange($data)
    {
        $user = parent::getUser();
        $userInfo = $user->getUserinfo();
        
        if(!is_object($user))
            throw new \LogicException("请登入后再操作!", false);

        if(!isset($data['cashid'])||(int)$data['cashid']<=0)
            throw new \LogicException("无效的cashid!", false);

        if(!isset($data['scurrency'])||(int)$data['scurrency']<=0)
            throw new \LogicException("兑换数量不能为空!", false);
        
        $map = array();
        $map['status'] = 1;
        $map['id'] = (int)$data['cashid'];
        
        //积分配置信息
        $integralCash = $this->get('db.integralcash')->findOneBy($map);
        
        if(!is_object($integralCash))
            throw new \LogicException("配置方案不存在或已被删除!", false);
        
        $scurrency = (int)abs($data['scurrency']);
        
        //计算
        $ecurrency = $scurrency/$integralCash->getScurrency()*$integralCash->getEcurrency();
        
        $map = array();
        $map['_multi'] = false;
        $map['id']['in'] = array($integralCash->getSid(),$integralCash->getEid());
        
        $integral =  $this->get('db.integral')->findBy($map);
        
        if(!isset($integral['data'])||count($integral['data'])!=2)
            throw new \LogicException("配置方案不存在或已被删除!", false);
        
        $cashField = array();
        foreach($integral['data'] as $item)
        {
            $cashField[$item->getId()] = $item;
        }
        
        $map = array();
        $map['mid'] =$userInfo->getId();
        $map['name'] = $cashField[$integralCash->getSid()]->getEname();

        $sInfo = $this->get('db.user_attr')->createOneIfNone($map);
        
        // 未进行兑换前的金额
        $recordData = array();
        $recordData[] = $this->_handleRecord($sInfo);
        
        //判断余额
        if((int)$sInfo->getValue()<$scurrency)
            throw new \LogicException(sprintf("%s 余额不足!", $cashField[$integralCash->getSid()]->getName()), false);
        
        //减 兑换源
        $sInfo->setValue((int)$sInfo->getValue()-$scurrency);
        
        $map['name'] = $cashField[$integralCash->getEid()]->getEname();
        $eInfo = $this->get('db.user_attr')->createOneIfNone($map);
        
        $recordData[] = $this->_handleRecord($eInfo);
        
        //加 兑换目标
        $eInfo->setValue((int)$eInfo->getValue()+$ecurrency);
        
        //更新
        $this->get('db.user_attr')->batchupdate(array($sInfo,$eInfo));
        
        //写入消费表
        self::recordIntegral($recordData, self::getTableName());

        return true;
    }
    
    private function _handleRecord($info)
    {
        $data = array();
        $data['title'] = $info->getTitle();
        $data['name'] = $info->getName();
        $data['value'] = $info->getValue();
        return $data;
    }
}