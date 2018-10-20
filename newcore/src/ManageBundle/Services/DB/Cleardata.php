<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年05月17日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 清除数据
* 
*/
class Cleardata extends AbstractServiceManager
{
    protected $table = 'Cleardata';
    
    protected $modelsData, $menusData, $modelForms;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function clear($info=null)
    {
        set_time_limit(1200);
        
        $info = $info?$info:parent::findBy(array());

        if(!isset($info['data'])||empty($info['data']))
            return false;
   
        //清除缓存
        $this->get('core.memcache')->flushAll();

        //限制删除的模型
        $limitData = array(
            'area',
            'auth_access', 
            'auth_access_area', 
            'auth_firewall', 
            'auth_group', 
            'auth_group_area', 
            'auth_rule', 
            'auth_theme', 
            'cache', 
            'files', 
            'models', 
            'model_attribute', 
            'model_form', 
            'model_form_attr', 
            'sessions', 
            'system_log', 
            'msglog', 
            'users', 
            'user_attr', 
            'userinfo', 
            'menus', 
            'domains', 
            'event_listen',
        );

        foreach($info['data'] as $item)
        {

           $map = array();
           $map['name'] = $item->getSrcmodel();

            //如果是核心表，则只允许清除数据
            if(in_array($item->getSrcmodel(), $limitData)) {
                $item->setType(1);
            }

            //如果是清除菜单数据，则需保存默认数据(manage和weixin Bundle的数据为系统默认基础数据)
            switch($map['name'])
            {
                //清除菜单数据需要保存源数据
                case 'menus':
                    self::getBaseMenusData();
                    break;
            }
            
            switch($item->getType())
            {
                
                case 1://清除数据,以删表再建表的方式

                
                case 2://直接删除表,以及entity类、数据服务、metadata元数据、model+form_model+views的相关记录
                    //过滤条件
                    $params = $this->get('core.common')->getQueryArray($item->getParams());

                    $model = $this->get('db.models')->findOneBy($map);

                    if(!is_object($model))
                        continue;
    
                    $bundle = $model->getBundle();
                    $serviceName = $model->getServiceName();
    
                    if(empty($bundle)||empty($serviceName))
                        continue;

                    if (!$this->container->has($serviceName)) //如果容器中没有定义该数据服务，则删除当前清除任务
                    {
                        parent::delete($item->getId(), $item);

                        continue;
                    }

                    //基于条件的清除数据
                    if($item->getType()==1 && $params)
                    {
                        $params['is_delete']['gte'] = 0;
                        $this->get($serviceName)->dbalDelete($params);
                        
                        continue;
                    }
                        
                    //执行删除表动作
                    self::_dropTable($serviceName, $bundle, $item->getType()==2 ? true : false);

            
                    //执行生成表动作(不重生entity类及元数据)
                    if($item->getType()==1) {
                        self::_refreshSchema($model);
                        //self::_createEntity($model);
                    }


                    //清除模型、表单数据
                    if($item->getType()==2) {
                        self::_delModelData($model);
                    }
                    break;

            }
            
            //重新生成基础数据
            switch($map['name'])
            {
                case 'menus':
                    self::_handleMenu($this->menusData, 0);
                    foreach($this->menusData as $info)
                    {
                        $this->get('db.menus')->_createBinaryNode($info);
                    
                        unset($info);
                    }
                    break;
            }

        }

    }
    
    public function getBaseMenusData()
    {
        $map = array();
        $map['bundle']['orX'][]['bundle'] = 'manage';
        $map['bundle']['orX'][]['bundle'] = 'weixin';
        
        $info = $this->get('db.menus')->findBy($map);

        $tmp = array();

        $this->menusData = $this->get('core.common')->getTree($info['data'],0, $tmp, false);

        unset($map);
        unset($tmp);
        unset($info);
    }
    
    /**
     * 处理菜单结构
     */
    protected function _handleMenu($info, $pid)
    {
        foreach($info as $item)
        {
            if(!is_object($item))
                continue;

            if(!is_object($item->menu))
                continue;

            $item->menu->setPid($pid);
            $item->menu->setBindmenu('');

            $this->get('db.menus')->systemAdd($item->menu);

            if(!is_object($item->menu))
                continue;

            if($item->nodes)
                self::_handleMenu($item->nodes, $item->menu->getId());
        }
    }

    /**
     * 删除表
     * @param string $serviceName       操作的服务名称
     * @param string $del               是否从服务列表中删除
     */
    private function _dropTable($serviceName,$bunlde=null, $del=false)
    {
        //try {
            $this->get($serviceName)->dropTable($bunlde, $del?$serviceName:null);
        //} catch (\Exception $e) {
        
        //}
    }
    
    /**
     * 生成表
     */
    private function _createEntity($model)
    {
        $this->get('core.controller.command')->createEntity($model);
    }
    
    /**
     * 生成表
     */
    private function _refreshSchema($model)
    {
        $this->get('core.controller.command')->refreshSchema($model);
    }
    /**
     * 删除模型数据
     * @param object $model
     */
    private function _delModelData($model)
    {

        //清除模型字段数据
        $this->get('db.model_attribute')->dbalUpdate(array('is_delete'=>1), array('model_id'=>$model->getId()));
        
        //清除模型数据
        $this->get('db.models')->delete($model->getId());
        
        //查询表单数据
        $modelForm = $this->get('db.model_form')->findBy(array('model_id'=>$model->getId()));
        
        if(!isset($modelForm['data']))
            return false;
        
        foreach($modelForm['data'] as $item)
        {
            //清除表单字段数据
            $this->get('db.model_form_attr')->dbalUpdate(array('is_delete'=>1), array('model_form_id'=>$item->getId()));
            
            //清除表单数据
            $this->get('db.models')->delete($item->getId());
            
            //清除模版配置数据
            $this->get('db.views')->dbalUpdate(array('is_delete'=>1), array('useform'=>$item->getId()));
        }
    }
}