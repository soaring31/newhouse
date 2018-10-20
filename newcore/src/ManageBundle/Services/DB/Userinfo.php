<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月14日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 会员信息
* 
*/
class Userinfo extends AbstractServiceManager
{
    protected $table = 'Userinfo';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    /**
     * 检查用户信息是否存在
     * @param unknown $uid
     * @return Ambigous <multitype:, multitype:array object >|Ambigous <boolean, unknown>
     */
    public function checkUserInfo($uid)
    {
        if($uid<=0)
            return array();

        $map = array();
        $map['uid'] = $uid;
        $userInfo = parent::findOneBy($map);
        
        //如果用户信息表数据存在则修改，不存在则添加
        if(is_object($userInfo))
            return $userInfo;
        
        $data = array();
        $data['uid'] = $uid;
    
        return parent::add($data, null, false);
    }
    
    /**
     * 变更组
     * @param int $uid
     * @param int $gid
     * @return boolean
     */
    public function changeUserGroup($uid, $gid, $cid=0, $userInfo=null)
    {
        if($gid<=0)
            return false;
        
        $map = array();
        $map['id'] = (int)$gid;
        
        //组分类数据
        $memGroup = $this->get('db.mem_group')->findOneBy($map);
        
        //如果组分类数据不存在则直接返回
        if(!is_object($memGroup))
            return false;
        
        //读用户信息表
        $userInfo = is_object($userInfo)?$userInfo:parent::checkInfo(array('uid'=>$uid));
        
        $userInfo->setUsertype($memGroup->getAid());
        $userInfo->setGroupid($memGroup->getId());
        $userInfo->setCid($cid);

        return parent::update($uid, array(), $userInfo, false);
    }
    
    /**
     * 当支付状态为无效(paystatus=2)时，对应用户佣金还原
     */
    public function restoreYongjin($info)
    {
        if (is_object($info) && $info->getPaystatus()==2)
        {
            $userId = $info->getUid();
    
            $info1 = parent::findOneBy(array('uid'=>$userId));
            $info1->setYongjin((int)$info->getAmount() + $info1->getYongjin());
            parent::update($userId, array(), $info1, false);
        }
    }
    
    /**
     * 申请提现后将减少对应佣金置
     * @param unknown $info
     */
    public function reduceYongjin($info)
    {
        if (is_object($info))
        {
            $userId = $info->getUid();
    
            $info1 = parent::findOneBy(array('uid'=>$userId));
            //获取提现金额
            $amount = method_exists($info, 'getAmount')?$info->getAmount():0;
            //减去相应金额数
            $info1->setYongjin($info1->getYongjin()-$amount);
            parent::update($userId, array(), $info1, false);
        }
    }
}