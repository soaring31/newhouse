<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月15日
*/
namespace CoreBundle\Services\Rbac;

use CoreBundle\Security\User\User;
use CoreBundle\Security\Role\Role;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/*
 * 有在进程里变化角色的需求(需要实时刷新权限)
 * 只针对当前用户，根据其角色(与分站有关)，分析权限、菜单
 * 
 * */
class Rbac extends ServiceBase implements RbacInterface
{
    protected $userChecker;
    protected $inheritsNum = 0;
    protected $inherits = array();
    protected $memTypes = array();
    protected $memGroup = array();
    protected $rulesArea = array();
    protected $isAreaManage = false;
    protected $inheritsArr = array();
    protected $attachRoles = array();
    protected $testArr = array();
    protected $authGroups = array();
    protected $attributes = array();
    
    protected $_handled = array();

    public function __construct(ContainerInterface $container, UserCheckerInterface $userChecker)
    {
        $this->container = $container;
        $this->userChecker = $userChecker;
    }

    /**
     * 权限检查
     * @param string $action
     * @param string $controller
     * @param string $bundle
     */
    public function isGranted($action=null, $controller=null, $bundle=null)
    {
        $user = $this->get('core.common')->getUser();
        
        if(!is_object($user))
        {
            //判断是否token认证
            $token = $this->get('request')->get('token', '');

            if($token)
            {
                $userInfo = $this->get('db.users')->findOneBy(array('token'=>$token), array(), false);
            
                if(!is_object($userInfo))
                    return false;
            
                self::loginHandle(new User($userInfo));
            
                $user = $this->get('core.common')->getUser();
    
                //验证通过;标记一下回调的auth_token
                $this->get('request')->request->set('auth_token', $token);
            }
        }

        $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;

        //创始人不检查权限
        if($mid==1)
            return true;

        // 08防火墙内的请求，全部有权限，包括游客
        if(empty($bundle)&&empty($controller)&&empty($action)&&self::checkFirewall())
           return true;
        
        //游客的权限到此为止        
        $rules = method_exists($user, 'getRules')?$user->getRules():'';
        if(empty($rules))
            return false;

        $map = array();
        $map['bundle'] = $bundle?$bundle:$this->get('core.common')->getBundleName();
        $map['bundle'] = strtolower(str_replace("Bundle","",$map['bundle']));
        $map['controller'] = $controller?$controller:$this->get('core.common')->getControllerName();
        $map['action'] = $action?$action:$this->get('core.common')->getActionName();
       
        // 内部跳转(index)方法不生成节点可以
        if($authRule = $this->get('db.auth_rule')->getData($map)) {
            if(isset($rules[$authRule['id']])) {
                return true;
            }
        }

        //判断当前请求(只限内部跳转方法，indexAction) 所对应的菜单的权限
        if($map['action']!='index') {
            return false;
        }

        if(!($menu = $this->get('db.menus')->findOneBy($map, array(), false))) {
            return false;
        }
  
        $attributes = $this->get('core.common')->getAttributes();
        if(isset($attributes['menus'][$menu->getId()])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 防火墙校验
     * 当前请求匹配08系统内置的防火墙规则的话，返回true.
     * @return boolean
     */
    public function checkFirewall()
    {
        $authFirewall = $this->get('db.auth_firewall')->getRule(array('status'=>1,'_multi'=>false));

        if(isset($authFirewall['data']))
        {
            foreach($authFirewall['data'] as $access)
            {
                $path = is_object($access)?$access->getPath():$access['path'];
                $ips = is_object($access)?$access->getIps():$access['ips'];

                if(empty($path)&&empty($path))
                    continue;

                try {

                    $matcher = new RequestMatcher($path, null, null, $ips?$ips:null);
                    
                    if($matcher->matches($this->get('request')))
                        return true;

    	        } catch (\Exception $e) {
    	            $this->get('core.common')->logicException($e);
    	        }
            }
        }

        return false;
    }
    
    /**
     * 自动升降级
     * @param object $userInfo
     * @param int $integral
     * @throws \InvalidArgumentException
     */
    public function autoUpdate($userInfo, $integral)
    {
        $info = $this->get('db.integral')->findOneBy(array('id'=>$integral));

        if(!is_object($info))
            throw new \InvalidArgumentException("无效的积分方案。");

        //积分属性
        $map = array();
        $map['mid'] = $userInfo->getId();
        $map['name'] = $info->getEname();
        $userAttr = $this->get('db.user_attr')->findOneBy($map);
        
        //根据数值计算等级(自动升级)
        $map = array();
        $map['aid'] = $userInfo->getUsertype();
        $map['mincurrency']['lte'] = is_object($userAttr)?(int)$userAttr->getValue():0;
        $map['maxcurrency']['gte'] = is_object($userAttr)?(int)$userAttr->getValue():0;

        $this->memGroup = $this->get('db.mem_group')->findOneBy($map, array(), false);

        $this->memGroup = is_object($this->memGroup)?$this->memGroup:$this->get('db.mem_group')->findOneBy(array('id'=>$userInfo->getGroupid()), array(), false);

        //更新userinfo数据
        if(is_object($this->memGroup)&&$userInfo->getGroupid()!=$this->memGroup->getId())
        {
            $userInfo->setGroupid($this->memGroup->getId());
            $this->get('db.userinfo')->update($userInfo->getId(), array(), $userInfo, false);
        }
    }
    
    /**
     * 付费升级
     * @param object $userInfo
     */
    public function autoUpdate2($userInfo)
    {
        $map = array();
        $map['id'] = (int)$userInfo->getGroupid();
        
        $this->memGroup = $this->get('db.mem_group')->findOneBy($map);

        //判断是否到期
        if($userInfo->getDuedate()<=0||$userInfo->getDuedate()>time())
            return true;
        
        unset($map['id']);

        $map['aid'] = (int)$userInfo->getUsertype();

        //取降级后的ID
        if((int)$this->memGroup->getOverdue()>0)
            $map['id'] = (int)$this->memGroup->getOverdue();

        $this->memGroup = $this->get('db.mem_group')->findOneBy($map, array('id'=>'asc'));
        
        //更新userinfo数据
        if(is_object($this->memGroup)&&$userInfo->getGroupid()!=$this->memGroup->getId())
        {
            $duedate = 0;

            //到期时间 = 上一次到期时间+(天数*86400)
            if((int)$this->memGroup->getOverdue()>0)                
                $duedate = $userInfo->getDuedate()+((int)$this->memGroup->getOverdue()*86400);

            $userInfo->setDuedate($duedate);
            $userInfo->setGroupid($this->memGroup->getId());
            
            $this->get('db.userinfo')->update($userInfo->getId(), array(), $userInfo, false);
        }
    }
    
    /**
     * 获取角色名称
     */
    public function getRolename()
    {
        return method_exists($this->memGroup, 'getName')?$this->memGroup->getName():'普通会员';
    }
    
    /**
     * 获取默认会员主题
     */
    public function getUsertplid()
    {
        return method_exists($this->memTypes, 'getUsertplid')?$this->memTypes->getUsertplid():$this->get('core.common')->getBundleName();
    }
    
    /**
     * 获取用户访问组,通过会员所在组获取其权限角色
     * @param int $uid
     */
    public function getAuthGroupAccess($uid)
    {
        $userInfo = $this->get('db.userinfo')->checkUserInfo($uid);

        
        if($userInfo->getUsertype()<=0)
            throw new \InvalidArgumentException("无效的会员分类。");
        
        if($userInfo->getGroupid()<=0)
            throw new \InvalidArgumentException("未定义会员组。");

        //会员组信息
        $this->memTypes = $this->get('db.mem_types')->findOneBy(array('id'=>$userInfo->getUsertype()), array(), false);

        if(!is_object($this->memTypes))
            throw new \InvalidArgumentException("无效的会员分类。");
        
        $mode = (int)$this->memTypes->getMode();
        
        switch($mode)
        {
            //自动升级模式
            case 1:
                self::autoUpdate($userInfo,$this->memTypes->getIntegral());
                break;
            //付费升级
            case 2:
                self::autoUpdate2($userInfo);
                break;
            default:
                //会员组分类信息
                $this->memGroup = $this->get('db.mem_group')->findOneBy(array('id'=>$userInfo->getGroupid()), array(), false);
                break;
        }
        
        if(!is_object($this->memGroup))
            throw new \InvalidArgumentException("无效的会员组。");

        //会员分类处理
        $roles = self::getAttachGroup($uid, $this->memTypes->getAttach());

        if($this->memGroup->getRole())
            $roles += explode(',', $this->memGroup->getRole());

        //反转去重
        $roles = array_flip($roles);
        $roles = array_flip($roles);
        return $roles;
    }
    
    /**
     * 计算属性组
     * @param int $uid         用户ID
     * @param string $attach   附加组ID
     */
    public function getAttachGroup($uid, $attach)
    {
        $roles = array();
        $integralAttr = array();
        
        if(empty($attach))
            return array();
        
        //用户属性表
        $userInfo = $this->get('db.userinfo')->createOneIfNone(array('uid'=>$uid));
        
        //会员组信息
        $map = array();
        $map['id']['in'] = explode(',', $attach);
        $memTypes = $this->get('db.mem_types')->findBy($map);
        
        if(empty($memTypes['data']))
            return array();

        foreach($memTypes['data'] as $group)
        {
            if((int)$group->getIntegral()<=0)
                continue;
            
            if(!isset($integralAttr[$group->getIntegral()]))
                $integralAttr[$group->getIntegral()] = $this->get('house.integral')->findOneBy(array('id'=>$group->getIntegral()), array(), false);
            
            if(empty($integralAttr[$group->getIntegral()]))
                continue;
            
            //用户属性表
            $map = array();
            $map['mid'] = $userInfo->getId();
            $map['name'] = $integralAttr[$group->getIntegral()]->getEname();
            $userAttr = $this->get('db.user_attr')->findOneBy($map, array(), false);
            
            //根据数值计算等级(自动升级)
            $map = array();
            $map['aid'] = $group->getId();
            $map['mincurrency']['lte'] = is_object($userAttr)?(int)$userAttr->getValue():0;
            $map['maxcurrency']['gte'] = is_object($userAttr)?(int)$userAttr->getValue():0;
            $memGroup = $this->get('db.mem_group')->findOneBy($map, array(), false);
            
            if(!is_object($memGroup))
                continue;
            
            $this->attachRoles[$group->getId()]['name'] = $group->getName();
            $this->attachRoles[$group->getId()]['role'] = $memGroup->getRole()?explode(',', $memGroup->getRole()):array();
            $this->attachRoles[$group->getId()]['mode'] = (int)$group->getMode();
            $this->attachRoles[$group->getId()]['subid'] = $memGroup->getId();
            $this->attachRoles[$group->getId()]['subname'] = $memGroup->getName();
            $this->attachRoles[$group->getId()]['ico'] = $memGroup->getIco();
            
            if($this->attachRoles[$group->getId()]['role'])
                $roles += $this->attachRoles[$group->getId()]['role'];
        }

        return $roles;
    }

 
    /**
     * 根据所有(含继承过来的)角色，得到所有拥有权限的节点id
     * @param array $authGroup
     */
    public function getAuthRules(array $authGroups)
    {
        $filter = array();
        foreach($authGroups as $item)
        {
            if($rules = $item->getRules()) {
                $filter[$item->getType()][] = $rules;
            }
        }

        $filter = array_map('self::_implode', $filter);
        $filter = array_map('self::_explode', $filter);
        
        //过淲黑名单(集差)
        $rules = isset($filter[0]) ? $filter[0] : array();
        if(isset($filter[1])) {
            $rules = array_diff($rules, $filter[1]);            
        }
        
        // 兼容之前的代码，将数组反转
        return array_flip($rules);
    }


    /**
     * 根据权限节点ids，得到有权限的后台菜单ids
     */
    public function getMenuNodes($rules)
    {
        $ids = array();
        $rules = array_keys($rules);

        if(empty($rules))
            return $ids;

        $map = array();
        $map['_multi'] = false;
        $map['status'] = 1;
        $map['id']['in'] = $rules;

        $authRule = $this->get('db.auth_rule')->findBy($map);

        $authRule = isset($authRule['data'])?$authRule['data']:array();

        foreach($authRule as $vo)
        {   
            //节点所绑定的菜单id
            $menuid = (int)$vo->getMenuid();

            if($menuid<=0)
                continue;
            
            //获取当前菜单的所有上级菜单
            $rs = $this->get('db.menus')->getParent($menuid, $ids);

            if(!isset($rs['data'])||empty($rs['data']))
                continue;
            
            foreach($rs['data'] as $voo)
            {
                $ids[] = $voo->getId();
            }
        }

        return array_flip($ids);
    }

    /**
     * 创建访问规则
     */
    public function createRule()
    {
        //防火墙不作权限检测的跳过写入规则
        if(self::checkFirewall())
            return true;
        
        $bundle = $this->get('core.common')->getBundleName();
        $bundles = $this->get('core.common')->getBundles();
    
        if(isset($bundles[$bundle]))
        {
            $map = array();
            $map['bundle'] = strtolower(str_replace("Bundle","",$bundle));
            $map['controller'] = $this->get('core.common')->getControllerName();
            $map['action'] = $this->get('core.common')->getActionName();

            $authRule = $this->get('db.auth_rule')->getData($map);
    
            if(empty($authRule))
            {
                $auth_rule = $this->get('db.auth_rule')->findOneBy($map, array(), false);
                if(!is_object($auth_rule))
                {
                    $data = $map;
                    $data['name'] = "";
                    $data['status'] = 1;
                    
                    //详情贡的节点，随列表页的详情页方法(这设计上要符合：列表控制器的详情页方法名=详情页的控制器名)
                    $curd = array('save'=>1,'delete'=>1,'show'=>1);
                    if($map['action']&&isset($curd[$map['action']]))
                    {
                        $map['action'] = $map['controller'];
                        unset($map['controller']);
                    }

                    $menus = $this->get('db.menus')->findOneBy($map, array(), false);

                    if(is_object($menus))
                    {
                        $data['type'] = 1;
                        $data['name'] = $menus->getName();
                        $data['menuid'] = $menus->getId();
                        $data['pmenuid'] = $menus->getPid();
                    }
        
                    switch($data['action'])
                    {
                        case 'save':
                            $data['name'] .= "添加";
                            break;
                        case 'delete':
                            $data['name'] .= "删除";
                            break;
                        case 'show':
                            $data['name'] .= "编辑";
                            break;
                    }
        
                    if($data['bundle']&&$data['controller']&&$data['action'])
                        $this->get('db.auth_rule')->dbalAdd($data);
                }
            }
        }
    }

    /**
     * 登陆处理
     * @param UserInterface $user
     * @param string $firewall
     * @return token
     */
    public function loginHandle(UserInterface $user, $firewall="08cmssharekey")
    {
        $str = uniqid(mt_rand(),1);
        $loginFlag = substr(sha1(sha1($str)), 0, 10);

        $this->userChecker->checkPostAuth($user);
        
        $user->setLoginFlag($loginFlag);

        $token = self::createToken($user, $firewall);
        
//         if ($this->container->isScopeActive('request')) {
//             $this->get('security.authentication.session_strategy')->onAuthentication($this->get('request'), $token);
//         }

        $this->get('security.token_storage')->setToken($token);
        
        $this->get('session')->set('_security_'.$firewall, serialize($token));
        
        //更新语言
        if ($user->getLocale())
            $this->get('session')->set('_locale', $user->getLocale());
        
        $this->get('session')->save();
        
        $user = $this->get('db.users')->findOneBy(array('id'=>$user->getId()));
        $user->setLogintime(time());
        $user->setLoginFlag($loginFlag);
        $user->setLoginip($this->get('core.common')->getClientIp());
        //更新用户表
        $this->get('db.users')->update($user->getId(), array(), $user, false);

        return $token;
    }
    
    /**
     * 重置Token
     * @param object $token
     */
    public function resetToken($token)
    {
        $user = $this->get('core.common')->getUser();
        
        $firewall = $token->getProviderKey();

        $token = self::createToken($user,$token->getProviderKey());
        
        $this->get('security.token_storage')->setToken($token);
        
        $this->get('session')->set('_security_'.$firewall, serialize($token));
        $this->get('session')->save();

        return $token;
    }

    /**
     * 创建认证token
     * @param UserInterface $user
     * @param string $firewall
     */
    public function createToken(UserInterface $user, $firewall)
    {
        $attributes = self::getRolesForUser($user);
        
        $roles = isset($attributes['roles'])?$attributes['roles']:array();

        unset($attributes['roles']);
        
        $token = new UsernamePasswordToken($user, 'password', $firewall, $roles);

        $token->setAttributes($attributes);
        
        return $token;
    }
    
    public function getRolesForUser($user)
    {
        if(!is_object($user)) {
            throw new \InvalidArgumentException('Rbac error : $user must be a object');
        }

        $attributes = array();
        $attributes['roles'] = array();
    
        $order = array();
        $order['sort'] = "asc";
    
        $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;
    
        //非创始人则检查权限
        if($mid==1)
        {
            //访问组
            $authGroup = $this->get('db.auth_group')->findOneBy(array(), $order, false);
            $attributes['roles'][] = new Role($authGroup);
            $attributes['rolename'] = '系统管理员';
            $attributes['isAreaManage'] = $this->isAreaManage;
            $attributes['rulesArea'] = $this->rulesArea;
            return $attributes;
        }
    
        //获取权限组
        $groupIds = self::getAuthGroupAccess($user->getId());

        $map = array();
        $map['_multi'] = false;
        $map['status'] = 1;
        $map['id'] = array('in'=>$groupIds?$groupIds:array(0));
        $map = $this->attachAreaToAuthGroupMap($map);
    
        //访问组
        $authGroup = $groupIds?$this->get('db.auth_group')->findBy($map, $order):array();
    
        $authGroup = isset($authGroup['data'])?$authGroup['data']:array();

        self::inherits($authGroup);

        $this->testArr = $this->authGroups;
        $attributes['roles'] = array_values($this->attributes);
 
        $attributes['rolename'] = method_exists($this->memGroup, 'getName')?$this->memGroup->getName():'普通会员';

        //根据规则获取有权限的菜单ID
        $attributes['menus'] = self::getMenuNodes(self::getAuthRules($this->authGroups));
        
        $attributes['attach'] = $this->attachRoles;

        //区域管理
        self::handleRulesArea($user->getId());
        
        $attributes['isAreaManage'] = $this->isAreaManage;
        
        $attributes['rulesArea'] = $this->rulesArea;

        return $attributes;
    }
    
    /**
     * 角色继承处理
     * 结果体现在 $this->authGroups,$this->attributes
     * 角色允许区分主站或分站
     */
    public function inherits($authGroup)
    {
        $_inherits = array();
        foreach($authGroup as $role)
        {
            //属性集
            $this->attributes[$role->getId()] = new Role($role);
            
            //认证组集
            $this->authGroups[$role->getId()] = $role;
            
            //判断是否加入已继承角色
            if(in_array($role->getId(), $this->inheritsArr, true))
                continue;

            $this->inheritsArr[] = $role->getId();

            if(!$role->getInherit())
                continue;
            
            $inherits = explode(',', $role->getInherit());

            foreach($inherits as $inherit)
            {
                if(in_array($inherit, $this->inheritsArr, true))
                    continue;
                
                $_inherits[] = (int)$inherit;
            }
        }

        foreach($_inherits as $k=>$v)
        {
            if(in_array($v, $this->inheritsArr, true))
                unset($_inherits[$k]);
        }

        //权限继承
        if(count($_inherits)>0)
        {
            $_inherits = array_unique($_inherits);

            $map = array();
            $map['_multi'] = false;
            $map['status'] = 1;
            $map['id']['in'] = $_inherits;           
            $map = $this->attachAreaToAuthGroupMap($map);
            
            //访问组
            $authGroup = $this->get('db.auth_group')->findBy($map, array('sort'=>'asc'));

            $authGroup = isset($authGroup['data'])?$authGroup['data']:array();    
            if(count($_inherits)>0)
                self::inherits($authGroup);
        }
    }

    /**
     * 为角色查询附加分站条件
     * @param array $map 初始查询条件
     * @return array 附加了分站限制的查询条件
     */
    public function attachAreaToAuthGroupMap(array $map = array())
    {
        if(empty($map['region'])) {
            // -1 为所有主分站可用的角色，0为主站，-2为所有分站
            $regions = array('-1');
            
            if($area = $this->get('core.area')->getArea()){
                $regions[] = '-2';
            }
            $regions[] = $area;
            $map['region']['in'] = $regions;
        }
        return $map;
    }        
    
    /**
     * 处理区域
     * @param int $uid
     * @return boolean
     */
    public function handleRulesArea($uid)
    {
        $rulesArea = array();
        $map = array();
        $map['uid'] = (int)$uid;
        $authAccessArea = $this->get('db.auth_access_area')->findBy($map);

        if(!isset($authAccessArea['data'])||empty($authAccessArea['data']))
            return false;
        
        $this->isAreaManage = true;
        
        $aids = array();
        foreach($authAccessArea['data'] as $item)
        {
            if((int)$item->getAid()) {
                $aids[] = (int)$item->getAid();
            }
        }
        
        if(empty($aids))
            return false;

        $map = array();
        $map['checked'] = 1;
        $map['id']['in'] = array_unique($aids);
        $authGroupArea = $this->get('db.auth_group_area')->findBy($map);
        
        if(!isset($authGroupArea['data'])||empty($authGroupArea['data']))
            return false;
             
        foreach($authGroupArea['data'] as $item)
        {
            if((int)$item->getRulesArea()) {
                $rulesArea[] = (int)$item->getRulesArea();
            }
        }
        $this->rulesArea = array_unique($rulesArea);
    }
    
    /**
     * 获取分站管理员标识
     * @return boolean
     */
    public function getAreaManage()
    {
        return $this->isAreaManage;
    }
    
    /**
     * 获取分站管理员分站数据
     * @return multitype:
     */
    public function getRulesArea()
    {
        return $this->rulesArea;
    }

    /**
     * 获取附加组
     */
    public function getAttachRoles()
    {
        return $this->attachRoles;
    }
    
    public function getAuthGroups()
    {
        return $this->authGroups;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    private function _implode($v)
    {
        return $v ? implode(",",$v) : '';
    }
    
    private function _explode($v)
    {
        return $v ? array_unique(explode(",",$v)) : array();
    }    
    
    /**
     * 为兼容之前代码暂时保留，请使用  getAuthRules 方法
     */
    public function getAuthGroup(array $authGroup)
    {
        return self::getAuthRules($authGroup);
    }    
}