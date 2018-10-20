<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月13日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Menus extends AbstractServiceManager
{
    protected $tablename;
    protected $table = 'Menus';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        $this->tablename = $this->get('core.common')->getTblprefix()."menus";
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_menus";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_menus.yml";
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        $data['level'] = 1;
        $data['sort'] = isset($_POST['sort'])?(int)$_POST['sort']:0;
        $data['pid'] = isset($data['pid'])?$data['pid']:$this->get('request')->get('pid', 0);
        $data['controller'] = isset($data['controller'])?strtolower($data['controller']):"";
        $data['action'] = isset($data['action'])?strtolower($data['action']):"";

        //去掉controller后缀
        if($data['controller']&&preg_match('/controller$/', $data['controller']))
            $data['controller'] = strtolower(substr($data['controller'],0,-10));

        //去掉action后缀
        if($data['action']&&preg_match('/action$/', $data['action']))
            $data['action'] = strtolower(substr($data['action'],0,-6));

        if(isset($data['identifier'])&&preg_match('/Bundle$/', $data['identifier']))
            $data['bundle'] = strtolower(substr($data['identifier'],0,-6));

        if($data['pid']>0)
        {
            $map = array();            
            $map['id'] = $data['pid'];            
            $map = self::handleMenuMap($map);
            
            $pmenu = $this->findOneBy($map);
            
            if(!is_object($pmenu))
                throw new \LogicException('上级菜单不存在或已被删除');

            $data['level'] = $pmenu->getLevel()+1;

            if(isset($data['identifier'])&&preg_match('/Bundle$/', $data['identifier']))
                $data['bundle'] = strtolower(substr($data['identifier'],0,-6));
            else
                $data['bundle'] = !isset($data['bundle'])||empty($data['bundle'])?$pmenu->getBundle():$data['bundle'];

            $this->get('request')->request->set('identifier', $data['bundle']);

            if($data['level']>=3)
            {
                if($data['level']==3)
                    $data['action'] = "index";
 
                $data['action'] = $data['action']?$data['action']:$pmenu->getAction();
                $data['controller'] = $data['controller']?$data['controller']:$pmenu->getController();

                if(empty($data['controller']))
                    throw new \LogicException('所属控制器不能为空');

                if(empty($data['action']))
                    throw new \LogicException('所属动作不能为空');

                $map = array();
                $map['bundle']     = $data['bundle'];
                $map['controller'] = $data['controller'];
                $map['action']     = $data['action'];
                
                $count = $this->count($map);

                if($count>0)
                    throw new \LogicException('菜单链接已存在！');
            }
        }
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }
    
    /**
     * 更新/修改
     * @see \CoreBundle\Services\AbstractServiceManager::update()
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        if(is_object($info))
            return parent::update($id, array(), $info, $isValid);
        
        $map = array();
        $map['id'] = $id;        
        $map = self::handleMenuMap($map);

        $info = parent::findOneBy($map);
        
        if(!is_object($info))
            throw new \LogicException('菜单不存在或已被删除!');
        
        $pid = $info->getPid();
        $level = $info->getLevel();
       
        $data['controller'] = isset($data['controller'])?strtolower($data['controller']):$info->getController();
        
        $data['action'] = isset($data['action'])?strtolower($data['action']):$info->getAction();

        if(isset($data['identifier'])&&preg_match('/Bundle$/', $data['identifier']))
            $data['bundle'] = strtolower(substr($data['identifier'],0,-6));
        else
            $data['bundle'] = !isset($data['bundle'])||empty($data['bundle'])?$info->getBundle():$data['bundle'];

        if(preg_match('/controller$/', $data['controller']))
            $data['controller'] = strtolower(substr($data['controller'],0,-10));
        
        if(preg_match('/action$/', $data['action']))
            $data['action'] = strtolower(substr($data['action'],0,-6));
        
        if($pid>0)
        {
            $pmenu = parent::findOneBy(array('id'=>$pid));
            
            if(!is_object($pmenu))
                throw new \LogicException('上级菜单不存在或已被删除!');
        }

        if($level>=3)
        {
            if(empty($data['controller']))
                throw new \LogicException('所属控制器不能为空!');

            if(empty($data['action']))
                throw new \LogicException('所属动作不能为空!');

            $map = array();
            $map['bundle'] = $data['bundle'];
            $map['controller'] = $data['controller'];
            $map['action'] = $data['action'];
            $map['id'] = array('neq'=>$id);
            $count = parent::count($map);

            if($count>0)
                throw new \LogicException('菜单已存!');
        }

        unset($data['pid']);
        unset($data['level']);
        
        unset($data['identifier']);
        
        return self::handleYmlData(parent::update($id, $data, null, $isValid));
    }
    
    /**
	 * 多条数据查询
	 * @param array $criteria  查询条件
	 * @param array $orderBy   排序
	 * @param boolean $ishide  
	 */
	public function getMenuList($pid, array $orderBy = null, $ishide=false)
	{
	    //默认Bundle
	    $bundle = $this->get('core.common')->getBundleName();

	    $map = array();

	    if($ishide)
            $map['is_hide'] = 0;

	    $map['bundle'] = strtolower(substr($bundle,0,-6));
	    
	    //在这里限制为当前用户有权限的菜单
	    $map = self::handleMenuMap($map);

	    //得到所有当前用户有权限的菜单
	    $menuInfo = self::getData($map);
	    //$menuInfo = parent::findBy($map, $orderBy);
	    //$menuInfo = $menuInfo['data'];
	    	    
	    if(isset($map['id'])) //????
	        $menuInfo = $this->get('core.common')->array_sort($menuInfo, 'sort');
	    
	    if(empty($menuInfo))
	        return array();
    
	    // 构造树形结构
	    $tmp = array();
	    $info = $this->get('core.common')->getTree($menuInfo,$pid, $tmp);

	    foreach($info as &$v)
	    {
	        //处理绑定菜单
	        $bindMenu = is_object($v->menu)?explode(',',$v->menu->getBindmenu()):(isset($v->menu['bindmenu'])?explode(',',$v->menu['bindmenu']):"");
	        if($bindMenu)
	        {
	            //dump($bindMenu);die();
    	        foreach($bindMenu as $item)
    	        {
    	            if(!isset($tmp[$item]))
    	                continue;
    	            $v->nodes[$item] = $tmp[$item];
    	        }
	        }
	    }

	    return $info;
	}
	
	/**
	 * 从第几级开始的菜单树形结构
	 * @param integer $level   从第几级开始
	 * @param array $orderBy   排序
	 * @param array $params    其它参数
	 */	
	public function getMenuByLevel($level, array $orderBy = null, array $params = array())
	{
	    //默认Bundle
	    $bundle = $this->get('core.common')->getBundleName();
	    
	    $map = array();
	    $map['level'] = array('gte' => (int)$level);
        if($bundle!='ManageBundle') {
	        $map['bundle'] = array('neq'=>'manage');	        
	    }
	    $map = array_merge($map, $params);
	    $map = self::handleMenuMap($map);

	    $menuInfo = parent::findBy($map, $orderBy);

	    if(!isset($menuInfo['data']))
	        return array();

	    return $this->get('core.common')->getTreeByLevel($menuInfo['data'], $level);
	}
	
	/**
	 * 获得菜单导航
	 */
	public function getMenuNav($controller)
	{
	    $menuNav = array();
	
	    if($controller=='index')
	        return $menuNav;
	
	    if($controller=='main')
	        return $menuNav;
	
	    $map = array();
	    $map['bundle'] = strtolower(substr($this->get('core.common')->getBundleName(),0,-6));
	    $map['controller'] = $controller;
	    $map['action'] = $this->get('core.common')->getActionName();
	
	    $menuInfo = self::getDataByController($map);

	    if(empty($menuInfo))
	        return $menuNav;

	    $map = array();
	    $map['pid'] = $menuInfo['pid'];
	    $map['is_hide'] = 0;
	    $map['status'] = 1;
	    $map = self::handleMenuMap($map);

	    $menuInfo = self::getData($map);
	    
	    if(empty($menuInfo))
	        return $menuNav;

	    $pmenu = self::getData(array('id'=>$map['pid']));

	    $menuNav['data'] = end($pmenu);

	    //分阶组合
	    $menuNav['childs'] = $this->get('core.common')->getTree($menuInfo);

	    //处理绑定菜单
	    $bindMenu = isset($menuNav['data']['bindmenu'])&&$menuNav['data']['bindmenu']?explode(',',$menuNav['data']['bindmenu']):array();

	    if($bindMenu){
	        foreach($bindMenu as $vid)
	        {
	            if(isset($menuNav['childs'][$vid]))
	                continue;
	            
	            $pmenu = self::getData(array('id'=>$vid));

	            $nodeInfo = parent::getNodeById($vid);
	
	            if(!isset($nodeInfo['data'])||empty($nodeInfo['data']))
	                continue;
	
	            $info = $this->get('core.common')->getTree($nodeInfo['data'], isset($pmenu[$vid]['pid'])?$pmenu[$vid]['pid']:0);
	
	            if(empty($info))
	                continue;
	
	            $menuNav['childs'][$vid] = end($info);
	        }
	    }
	    
	    unset($map);
	    unset($pmenu);
	    unset($nodeInfo);
	    unset($bindMenu);
	    unset($controller);

	    return $menuNav;
	}

	
	protected function handleYmlData($data)
	{
	    $result = array();

        $map = array();
        $map['status'] = 1;
        $map['order'] = 'sort|asc,id|asc';
        $info = parent::findBy($map);

        if(isset($info['data']))
        {
            foreach($info['data'] as $item)
            {
                $result[$item->getBundle()]['id'][$item->getId()]['id'] = $item->getId();
                $result[$item->getBundle()]['id'][$item->getId()]['pid'] = $item->getPid();                
                $result[$item->getBundle()]['id'][$item->getId()]['sort'] = $item->getSort();
                $result[$item->getBundle()]['id'][$item->getId()]['name'] = $item->getName();                
                $result[$item->getBundle()]['id'][$item->getId()]['level'] = $item->getLevel();
                $result[$item->getBundle()]['id'][$item->getId()]['ename'] = $item->getEname();
                $result[$item->getBundle()]['id'][$item->getId()]['status'] = $item->getStatus();                
                $result[$item->getBundle()]['id'][$item->getId()]['bundle'] = $item->getBundle();
                $result[$item->getBundle()]['id'][$item->getId()]['action'] = $item->getAction();
                $result[$item->getBundle()]['id'][$item->getId()]['remark'] = $item->getRemark();
                $result[$item->getBundle()]['id'][$item->getId()]['models'] = $item->getModels();
                $result[$item->getBundle()]['id'][$item->getId()]['is_hide'] = $item->getIsHide();
                $result[$item->getBundle()]['id'][$item->getId()]['bindmenu'] = $item->getBindmenu();
                $result[$item->getBundle()]['id'][$item->getId()]['category'] = $item->getCategory();
                $result[$item->getBundle()]['id'][$item->getId()]['urlparams'] = $item->getUrlparams();
                $result[$item->getBundle()]['id'][$item->getId()]['controller'] = $item->getController();
                $result[$item->getBundle()]['id'][$item->getId()]['category_models'] = $item->getCategoryModels();
                $result[$item->getBundle()]['id'][$item->getId()]['checked'] = $item->getChecked();
                
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['id'] = $item->getId();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['pid'] = $item->getPid();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['sort'] = $item->getSort();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['name'] = $item->getName();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['level'] = $item->getLevel();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['ename'] = $item->getEname();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['status'] = $item->getStatus();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['bundle'] = $item->getBundle();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['action'] = $item->getAction();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['remark'] = $item->getRemark();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['models'] = $item->getModels();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['is_hide'] = $item->getIsHide();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['bindmenu'] = $item->getBindmenu();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['category'] = $item->getCategory();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['urlparams'] = $item->getUrlparams();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['controller'] = $item->getController();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['category_models'] = $item->getCategoryModels();
                $result[$item->getBundle()]['pid'][$item->getPid()][$item->getId()]['checked'] = $item->getChecked();
                
                $result[$item->getBundle()]['other'][$item->getController()][$item->getAction()]['id'] = $item->getId();
                $result[$item->getBundle()]['other'][$item->getController()][$item->getAction()]['pid'] = $item->getPid();
                $result[$item->getBundle()]['other'][$item->getController()][$item->getAction()]['level'] = $item->getLevel();
            }
        }

        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);

        unset($info);

        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        unset($result);
        return $data;
	}

	/**
	 * 直接从文件中读取
	 * @param array $criteria
	 * @return multitype:
	 */
    public function getData(array $criteria)
    {

        $criteria['bundle'] = isset($criteria['bundle'])?$criteria['bundle']:strtolower(substr($this->get('core.common')->getBundleName(),0,-6));

        $info = $this->get('core.common')->S($this->stag);

        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
            
            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if(!isset($info[$criteria['bundle']]))
            return array();
        
        if(isset($criteria['id']))
        {
            if(!is_array($criteria['id']))
                $criteria['id'] = array('in'=>array($criteria['id']));

            $result = array();

            foreach( end($criteria['id']) as $key)
            {
                if(!isset($info[$criteria['bundle']]['id'][$key]))
                    continue;

                if(isset($criteria['pid'])&&$info[$criteria['bundle']]['id'][$key]['pid']!=$criteria['pid'])
                    continue;

                $result[$key] = $info[$criteria['bundle']]['id'][$key];
            }

            return isset($criteria['is_hide'])&&$criteria['is_hide']==0?self::_handleHide($result):$result;
        }elseif(isset($criteria['pid'])){

            if(!is_array($criteria['pid']))
                $criteria['pid'] = array('in'=>array($criteria['pid']));
            
            $result = array();

            foreach( end($criteria['pid']) as $key)
            {
                
                if(!isset($info[$criteria['bundle']]['pid'][$key]))
                    continue;
                
                $result += $info[$criteria['bundle']]['pid'][$key];
            }

            return isset($criteria['is_hide'])&&$criteria['is_hide']==0?self::_handleHide($result):$result;
        }
        
        return isset($criteria['is_hide'])&&$criteria['is_hide']==0?self::_handleHide($info[$criteria['bundle']]['id']):$info[$criteria['bundle']]['id'];
    }
    
    public function getDataByController(array $criteria)
    {
        $criteria['bundle'] = isset($criteria['bundle'])?$criteria['bundle']:$this->get('core.common')->getBundleName();
        
        $info = $this->get('core.common')->S($this->stag);
        
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
        
            $this->get('core.common')->S($this->stag, $info, 86400);
        }
        
        if(!isset($info[$criteria['bundle']]['other']))
            return array();
        
        if(!isset($criteria['controller']))
            return $info[$criteria['bundle']]['other'];
        
        if(!isset($info[$criteria['bundle']]['other'][$criteria['controller']]))
            return array();
        
        if(!isset($criteria['action']))
            return $info[$criteria['bundle']]['other'][$criteria['controller']];
        
        if(!isset($info[$criteria['bundle']]['other'][$criteria['controller']][$criteria['action']]))
            return array();
        
        return $info[$criteria['bundle']]['other'][$criteria['controller']][$criteria['action']];
    }
    
    /**
     * 处理菜单查询条件，增加权限限制条件，并更新 token 中的权限属性
     * 约束查询到的只能是当前用户有权限的菜单
     * @param array $map
     */
    protected function handleMenuMap(array $map)
    {
        $map['_multi'] = isset($map['_multi']) ? $map['_multi'] : false;
        $onlymine = isset($map['onlymine']) && !$map['onlymine'] ? false : true;
        unset($map['onlymine']);

        // 不限制为当前会员权限内的菜单查询
        if($onlymine === false) {
            return $map;
        }

        $user = $this->get('core.common')->getUser();
         
        // 如为创始人，保持 $map 不变(这样的话，是否创始人的菜单权限难于得到即时更新???)
        $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;
        if($mid==1) {
            return $map;
        }
         
        $attributes = $this->get('core.common')->getAttributes();
    
        $menus = isset($attributes['menus'])&&$attributes['menus']?$attributes['menus']:array(0);
    
        $map['id']['in'] = $menus?array_keys($menus):array(0);
    
        return $map;
    }
    
    /**
     * 处理不可见数据
     * @param array $result
     */
    private function _handleHide(array $result, $flag='id')
    {
        foreach($result as $k=>$v)
        {
            switch($flag)
            {
                case 'pid':
                    foreach($v as $kk=>$vv)
                    {
                        if($vv['is_hide'])
                            unset($result[$k][$kk]);
                    }
                    break;
                default:
                    if($v['is_hide'])
                        unset($result[$k]);
                    break;
            }
        }
        
        return $result;
    }
}