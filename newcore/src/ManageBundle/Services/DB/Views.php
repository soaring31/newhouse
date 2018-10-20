<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月25日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Views extends AbstractServiceManager
{
    protected  $table = 'Views';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        $data['c_time'] = time();
        $this->_check($data);
        $count = parent::count(array('name'=>$data['name']));
        
        if($count>0)
            throw new \LogicException("模版名称已存在");
        
        return parent::add($data, $info, $isValid);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        if(!is_object($info))
            $info = parent::findOneBy(array('id'=>$id));

        $data['name'] = $info->getName();
        $data['title'] = $info->getTitle();
        $data['bundle'] = $info->getBundle();
        $data['controller'] = $info->getController();

        return parent::update($id, $data, $info); 
    }
    
    private function _check(array &$data)
    {
        $name = isset($data['name'])?trim($data['name']):'';
        
        if(empty($name))
            throw new \LogicException("模版名称不能为空");
        
        $name = explode(":",$name);
        
        if(count($name)!=3)
            throw new \LogicException("名称格式不能，正确格式为 【Bundle:控制器名:模版名】");
        
        //单词首字母大写
        $name[0] = ucwords($name[0]);
        $name[1] = ucwords($name[1]);
        
        $bundlePath = $this->get('core.common')->getBundlePath($name[0]);

        if(empty($bundlePath))
            throw new \LogicException(sprintf('【%s】Bundle不存在', $name[0]));
        
        $bundlePath .= "Controller".DIRECTORY_SEPARATOR.$name[1]."Controller.php";
        
        if (!file_exists($bundlePath))
            throw new \LogicException(sprintf('【%s】控制器不存在', $name[1]."Controller"));
        
        $data['bundle'] = $name[0];
        
        $data['controller'] = $name[1]."Controller.php";
        
        $data['name'] = implode(":", $name);
    }
}