<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月17日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 看房团信息
* 
*/
class Showingsnews extends AbstractServiceManager
{
    protected $table = 'Showingsnews';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    public function addShowingsnews($tel,$pid,$aid){
        $_data = array('topic' => $pid, 'phone' => $tel,'intention'=>$aid,'checked' => '1','models' => 'topic');
        return $this->get('house.showingsnews')->add($_data, null, false);
    }
}