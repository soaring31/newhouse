<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年04月19日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 活动报名表
* 
*/
class PromotionOrder extends AbstractServiceManager
{
    protected $table = 'PromotionOrder';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    public function addPromotionOrder($tel,$pid){

        $_data = array('promotion_id' => $pid, 'tel' => $tel,'checked' => '1');
        return $this->get('house.promotion_order')->add($_data, null, false);
    }

}