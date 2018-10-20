<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月09日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 佣金提取
* 
*/
class Wages extends AbstractServiceManager
{
    protected $table = 'Wages';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        $result = parent::add($data, $info, $isValid);

        //减少对应佣金数
        $this->get('db.userinfo')->reduceYongjin($result);
        
        return $result;
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $result = parent::update($id, $data, $info, $isValid);
        
        //当支付状态为无效(paystatus=2)时，对应用户佣金还原
        $this->get('db.userinfo')->restoreYongjin($result);
        
        return $result;
    }
}