<?php
/**
* @copyright Copyright (c) 2008 – 2018 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2018年04月07日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 楼盘附属信息
* 
*/
class HousesInfo extends AbstractServiceManager
{
    protected $table = 'HousesInfo';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    /**
     * 获取建筑类型
     * 王晓宇
     * @param integer $buildType 建筑类型ID
     *
     * @return array
     */
    public function getBuildType($buildType)
    {
        //拆分成数组
        $typeArray = explode(',', $buildType);
        $tslp      = array('');
        foreach ($typeArray as $key => $value) {
            switch ($value) {
                case 1:
                    array_push($tslp, "板楼");
                    break;
                case 2:
                    array_push($tslp, "塔楼");
                    break;
                case 3:
                    array_push($tslp, "板塔结合");
                    break;
                case 4:
                    array_push($tslp, "独栋别墅");
                    break;
                default:
                    array_push($tslp, "暂无");
                    break;
            }
        }
        //合并成字符串
        $tslp = trim(implode(',', $tslp), ",");
        return $tslp;
    }
}