<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年9月5日
 */
namespace CoreBundle\Services\DataTrans;

use Symfony\Component\DependencyInjection\ContainerInterface;

//core.eximphouse
class EximpHouse extends EximpBase
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function testExfunx($val,$expm=''){
    	return "$val.$expm";
    }

    // core.eximphouse:xiaoquDetail:(row) 小区介绍
    public function xiaoquDetail($val,$row=array()){ 
    	if($row['leixing']==2 && !empty($row['xqjs'])){
    		$val = $row['xqjs'];
    	}
    	return $val;
		//'xqjs' => 1, //小区介绍 -=> content
		//多余项目: 两个content (继承/覆盖)
    }

}


/* 

*/

