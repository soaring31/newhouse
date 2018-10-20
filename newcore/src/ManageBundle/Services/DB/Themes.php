<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月10日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 主题表
* 
*/
class Themes extends AbstractServiceManager
{
    protected $table = 'Themes';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $info=null, $isValid=true)
    {
        if(!isset($data['ename'])||empty($data['ename']))
            throw new \InvalidArgumentException("标识不能为空");
        
        $data['ename'] = ucwords($data['ename']);
        
        //增加Bundle后缀
        if (!preg_match('/Bundle$/', $data['ename']))
            $data['ename'] .= "Bundle";

        $datas = array();
        $datas['dir'] = "site";
        $datas['structure'] = 1;
        $datas['format'] = "yml";
        $datas['bundle'] = trim($data['ename']);

        try {
            $this->get('core.controller.command')->CreateBundle($datas, false);
        } catch (\Exception $e) {
        
        }
        
        $map = array();
        $map['ename'] = $datas['bundle'];
        $count = parent::count($map);
        
        if($count>0)
            throw new \InvalidArgumentException(sprintf("标识%s已存在!", $data['ename']));

        return parent::add($data, $info, $isValid);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        unset($data['ename']);
        return parent::update($id, $data, $info, $isValid);
    }
}