<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月28日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 地铁线路
* 
*/
class CateLine extends AbstractServiceManager
{
    protected $table = 'CateLine';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    /**
     * 根据ID查询属性表中的名称
     * @param array $id ID
     * @return array $name 名称
     */
    public function findCateLine($id)
    {

        if ($id) {
            $findcate = $this->get('house.cateline')->findOneBy(array('is_delete' => 0, 'id' => $id));
            if ($findcate) {
                //返回名称
                return $findcate->getName();
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}