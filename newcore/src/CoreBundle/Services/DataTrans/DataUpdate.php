<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年7月27日
 */
namespace CoreBundle\Services\DataTrans;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DataUpdate extends DataTrans
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 更新一个菜单,把导出的$mfile,导入到旧系统$pid下
     */
    public function updOneMenu($mfile,$pid)
    {
        //
    }

    /**
     * 更新一个模型,把导出的$mfile,导入到旧系统
     */
    public function updOneModel($mfile)
    {
        //
    }


}


/* 

*/

