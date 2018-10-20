<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月27日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 投票
* 
*/
class Vote extends AbstractServiceManager
{
    protected $table = 'Vote';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $pinfo=null, $isValid=true)
    {
        $result = parent::add($data, $pinfo, $isValid);

        //获取总积分
        $totalIntegral = $this->get('db.user_attr')->getIntegral();
        
        //积分增减
        $this->get('db.user_attr')->operateIntegral(array('name'=>'add'.self::getTableName()));

        //积分记录
        $this->get('db.spend')->recordIntegral($totalIntegral,self::getTableName());
        
        return $result;
    }
}