<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月20日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 周边配套
*
*/
class Around extends AbstractServiceManager
{
    protected $table = 'Around';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function relationByAround($info)
    {
        $geohash = $info->getGeohash();
        $geokey = substr($geohash, 0, 5).'%'; //相似度为5个字符串
        $zbmaps = $info->getMap()?explode(',', $info->getMap()):array();

        if(count($zbmaps)<2)
            return false;
        $data = $this->get('house.houses')->findBy(array('geohash' => array('like' => $geokey)));

        if(empty($data['data']))
            return false;

        $_form_id = $this->get('request')->get('_form_id', 0);
        $this->get('request')->request->set('_form_id', 0);

        foreach ($data['data'] as $val)
        {
            $maps = explode(',', $val->getMap());
            
            if(count($maps)<2)
                continue;

            $distance = $this->get('core.common')->getDistance($zbmaps[1],$zbmaps[0],$maps[1],$maps[0]);
            if($distance <= 3000) //距离小于3公里
            {
                $_data = array('aid' => $info->getId(), 'fromid' => $val->getId(), 'name' => $val->getName());
                return $this->get('house.houses_arc')->add($_data, null, false);
            }
        }
        $this->get('request')->request->set('_form_id', $_form_id);
        return true;
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