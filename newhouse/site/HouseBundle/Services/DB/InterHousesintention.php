<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年09月27日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 楼盘意向
* 
*/
class InterHousesintention extends AbstractServiceManager
{
    protected $table = 'InterHousesintention';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    public function addInterHousesintention($tel,$yid,$aid,$area,$uid){

        $_data = array('dyfl' => $yid, 'tel' => $tel,'aid'=>$aid,'area'=>$area,'uid'=>$uid,'checked' => '1');
        return $this->get('house.inter_housesintention')->add($_data, null, false);
    }
}