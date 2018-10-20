<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年09月29日
 */
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 楼盘分销推荐人
 *
 */
class Recommend extends AbstractServiceManager
{
    protected $table = 'Recommend';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $info = null, $isValid = true)
    {
        $result = new \stdClass();
        if (isset($data['tel']))
        {
            $map = array();
            $map['tel'] = $data['tel'];
            $result = self::findOneBy($map, array('id'=>'desc'));
        }

        if(method_exists($result, 'getStatus') && $result->getStatus()!=4 && $result->getStatus()!=5 && $result->getDuetime()>time())
            throw new \LogicException('此用户已被推荐');

        //获得用户信息
        $user = self::getUser();
        if (is_object($user))
        {
            $uid = (int)$user->getId();
            
            // 已推荐朋友个数
            $count = parent::count(array('uid'=>$uid));
            // 已无效客户个数
            $uncount = self::getSellUnnum();
    
            //	允许推荐朋友个数
            $sellpoeplenum = $this->get('db.mconfig')->findOneBy(array('name'=>'sellpoeplenum'), array(), false);
            //  无效客户个数
            $sellunnum = $this->get('db.mconfig')->findOneBy(array('name'=>'sellunnum'), array(), false);
            
            if (!is_object($sellpoeplenum) || !is_object($sellunnum))
                throw new \LogicException('配置信息错误');
    
            if ($count >= $sellpoeplenum->getValue())
                throw new \LogicException('推荐人数已上限');
            elseif ($uncount >= $sellunnum->getValue())
                throw new \LogicException('无效人数已上限');
    
            $info1 = $this->get('db.userinfo')->findOneBy(array('uid'=>$uid), array(), false);
            if (is_object($info1))
            {
                // 统计推荐朋友个数
                $info1->setPnum($count + 1);
                $this->get('db.userinfo')->update($uid, array(), $info1, false);
            }
        }
            
        // 获取推荐有效时间（单位：天）
        $config = parent::get('db.mconfig')->findOneBy(array('name'=>'selltime'), array(), false);
        // 如果有推荐有效时间则加上
        if (is_object($config))
            $data['duetime'] = time() + (method_exists($config, 'getValue')?(int)$config->getValue():0)*24*60*60;
        
        return parent::add($data, $info, $isValid);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        // 如果状态为无效（status=5）,将过期时间置零
        if (isset($data['status']) && $data['status']==5)
            $data['duetime'] = 0;
        
        return parent::update($id, $data, $info, $isValid);
    }
    
    /**
     * 单条数据查询
     * @see \CoreBundle\Services\AbstractServiceManager::findOneBy()
     */
    public function findOneBy(array $criteria, array $orderBy = array(), $flag=true)
    {
        self::updateSellUnnum();
        
        return parent::findOneBy($criteria, $orderBy , $flag);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = 2000, $offset = 1, $groupBy='')
    {
        self::updateSellUnnum();

        return parent::findBy($criteria, $orderBy ,$limit, $offset, $groupBy);
    }
    
    /**
     * 更新 db.userinfo 的无效用户个数
     */
    protected function updateSellUnnum()
    {
        //获得用户信息
        $user = self::getUser();
        if (is_object($user))
        {
            $unnum = self::getSellUnnum();
            
            $uid = (int)$user->getId();
            $info = parent::get('db.userinfo')->findOneBy(array('uid'=>$uid), array(), false);
            if ($info->getUnnum() != $unnum)
            {
                $info->setUnnum($unnum);
                parent::get('db.userinfo')->update($info->getId(), array(), $info, false);
            }
        }
    }
    
    /**
     * 获取无效用户个数
     */
    protected function getSellUnnum()
    {
        //获得用户信息
        $user = self::getUser();
        if (is_object($user)){
            $map = array();
            $map['uid'] = (int)$user->getId();
            $map['duetime']['lt'] = time();
            $map['status']['neq'] = 4;
            return parent::count($map);
        }
        return 0;
    }
}