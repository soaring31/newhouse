<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 二手
* 
*/
class Sale extends AbstractServiceManager
{
    protected $table = 'Sale';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $pinfo=null, $isValid=true)
    {
        $user = parent::getUser();
        if(is_object($user))
            $groupId = $user->getUserinfo()->getGroupid();
        
        //特殊字段
        $data['refreshdate'] = time();
    
        if (isset($groupId))
        {
            $config = self::get('house.userconfig')->findOneBy(array('role'=>$groupId), array(), false);
            if ($config && $user->getMid() != 1)
            {
                //当有会员配置时的操作
                $map = array();
                $map['operation'] = $user->getUsername();
                $map['create_time']['gt'] = strtotime(date('Y-m-d'));
                $map['name'] = '添加';
                $map['models']['orX'][]['models']='rent';
                $map['models']['orX'][]['models']='sale';
                
                //今日已发布数量
                $count = self::get('db.system_log')->count($map);
                
                //租售日发布数量
                $rentdrenum = $config->getRentdrenum();
                
                if ($count < $rentdrenum)
                    $result = parent::add($data, $pinfo, $isValid);
                else
                    throw new \LogicException("操作失败，您已经超过最大日发布数量!");
            } else
                //当没有会员配置时的操作,允许进行添加操作
                $result = parent::add($data, $pinfo, $isValid);
    
            //获取总积分
            $totalIntegral = $this->get('db.user_attr')->getIntegral();
                
            //积分增减
            $this->get('db.user_attr')->operateIntegral(array('name'=>'add'.self::getTableName()));
            
            //积分记录
            $this->get('db.spend')->recordIntegral($totalIntegral,self::getTableName());
            
            //经纪人或经纪公司数量统计
            self::saleAdd($result);
        } else
            $result = parent::add($data, $pinfo, $isValid);

        return $result;
    }
    
    public function delete($id, $info='')
    {
        $obj = parent::findOneBy(array('id'=>$id), array(), false);

        $result = parent::delete($id, $info);

        //获取总积分
        $totalIntegral = $this->get('db.user_attr')->getIntegral();
        
        //积分增减
        $this->get('db.user_attr')->operateIntegral(array('name'=>'del'.self::getTableName()));
        
        //积分记录
        $this->get('db.spend')->recordIntegral($totalIntegral,self::getTableName());

        //经纪人或经纪公司数量统计
        self::saleDel($obj);

        return $result;
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $oldValid = parent::findOneBy(array('id'=>$id), array(), false)->getValid();
        
        $result = parent::update($id, $data, $info, $isValid);

        //经纪人或经纪公司数量统计
        self::saleUpd($result, $oldValid);
        
        return $result;
    }

    public function saleAdd($obj)
    {
        if (($obj->getValid())>time())
            self::_shuliang($obj, 1);  // 数量+1
    }
    
    public function saleUpd($obj, $oldValid)
    {
        //数据本身是未上架，修改为上架之后则需  +1
        if ($oldValid<time() && ($obj->getValid())>time())
            self::_shuliang($obj, 1);  // 数量+1

        if ($oldValid>time() && ($obj->getValid())<time())
            self::_shuliang($obj, 0);  // 数量-1
    }

    public function saleDel($obj)
    {
        $uid = $obj->getUid();
        $cid = $obj->getCid();
        //删除一个已上架的，所以需要 -1
        if (($obj->getValid())>time())
        {
            //更新经纪人所发布的sale数量 -1
            if($uid > 0)
                self::_gx(array('uid'=>$uid), 0);
            
            //更新经纪公司所有的sale数量 -1
            if($cid > 0)
                self::_gx(array('uid'=>$cid), 0, false);
        }
    }
    
    /**
     * 手工刷新操作
     * @param array $data
     * @param boolean $isPay 付费刷新
     */
    public function refresh(array $data, $isPay = false)
    {
        $map = array();
        $map['id'] = isset($data['id'])?(int)$data['id']:0;
        $sale = parent::findOneBy($map);

        if(!is_object($sale))
            throw new \LogicException("二手房源不存在或已被删除!");

        $user = $this->get('core.common')->getUser();
        
        if(!is_object($user)||!is_object($user->getUserinfo()))
            throw new \LogicException("请重新登陆!");

        $userinfo = $user->getUserinfo();

        //每日可刷新总数量
        $totalCount = 0;
        if (strtotime(date('Y-m-d',$userinfo->getDuedate())) > time())
        {
            $totalCount = $userinfo->getRefreshs();
        } else {
            $map = array();
            $map['area'] = (int)$this->get('core.area')->getArea();
            $map['name'] = 'frefresh';
            $result = $this->get('db.mconfig')->findOneBy($map, array(), false);
            $totalCount = is_object($result)?(int)$result->getValue():0;
        }
        
        //查询日志表
        $map = array();
        $map['uid'] = $user->getId();
        $map['type'] = 'refresh';
        $map['create_time']['gt'] = strtotime(date('Y-m-d'));
        $count = $this->get('db.system_log')->count($map);

        //日志记录 > 每天可发布数量
        if ($count>=$totalCount && !$isPay)
            throw new \LogicException("免费次数不足，刷新失败!");
        
        //写进日志表
        $data = array();
        $data['type'] = 'refresh';
        $data['name'] = '二手房刷新';
        $data['models'] = 'sale';
        $data['title'] = $this->get('request')->getClientIp();
        $data['operation'] = is_object($user)?$user->getUsername():'游客';
        $data['uid'] = $user->getId();
        $this->get('db.system_log')->add($data, null, false);

//         if($userinfo->getRefreshs()<=0)
//             throw new \LogicException("免费次数不足，刷新失败!");
//         //扣一次
//         $userinfo->setRefreshs($userinfo->getRefreshs()-1);
        
//         //更新用户信息
//         $this->get('db.userinfo')->update($userinfo->getId(), array(), $userinfo, false);
        
        //更新二手房刷新时间
        $sale->setRefreshdate(time());
        return parent::update($sale->getId(), array(), $sale, false);
    }
    
    /**
     * 手工刷新置顶
     * @param array $data
     * @throws \LogicException
     * @return boolean
     */
    public function refreshtop(array $data)
    {
        $map = array();
        $map['id'] = isset($data['id'])?(int)$data['id']:0;
        $sale = parent::findOneBy($map);
    
        if(!is_object($sale))
            throw new \LogicException("二手房源不存在或已被删除!");
    
        $user = $this->get('core.common')->getUser();
    
        if(!is_object($user)||!is_object($user->getUserinfo()))
            throw new \LogicException("请重新登陆!");
    
        $userinfo = $user->getUserinfo();
    
        if($userinfo->getTops()<=0)
            throw new \LogicException("免费次数不足，置顶失败!");
    
        //扣一次
        $userinfo->setTops($userinfo->getTops()-1);
    
        //更新用户信息
        $this->get('db.userinfo')->update($userinfo->getId(), array(), $userinfo, false);
    
        //更新二手房置顶时间
        $inittime = max($sale->getZjdate(),time());
        $sale->setZjdate($inittime + 86400);
        
        return parent::update($sale->getId(), array(), $sale, false);
    }
    
    //$operate 1为加，0为减
    protected function _shuliang($obj, $operate)
    {
        if (method_exists($obj, 'getValid'))
        {
            $uid = array();
            $cid = array();
            $infos = $this->get('house.sale')->findOneBy(array('id'=>$obj->getId()), array(), false);
            $uid['uid'] = $infos->getUid();
            $cid['uid'] = $infos->getCid();
        
            //更新经纪人所发布的sale数量
            if(!empty($uid['uid']))
                self::_gx($uid, $operate);

            //更新经纪公司所有的sale数量
            if(!empty($cid['uid']) && $cid['uid']>0)
                self::_gx($cid, $operate, false);
        }
    }
    
    protected function _gx($map, $operate, $isjjr = true)
    {
        $info = $this->get('db.userinfo')->createOneIfNone($map);
        if(!is_object($info))
            throw new \LogicException("数据不存在或已被删除!");
        
        if ($operate)
            if ($isjjr)
                $info->setSales($info->getSales()+1);
            else 
                $info->setCsales($info->getCsales()+1);
        else
            if ($isjjr)
                $info->setSales($info->getSales()>0?$info->getSales()-1:0);
            else 
                $info->setCsales($info->getCsales()>0?$info->getCsales()-1:0);

        $this->get('db.userinfo')->setNoCheck(true);
        $this->get('db.userinfo')->update($map['uid'], array(), $info, false);
    }
}