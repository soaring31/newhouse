<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年1月14日
 */
namespace CoreBundle\Services\Rbac\Auth;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AuthAbstract extends ServiceBase implements AuthInterface
{
    //表前缀
    protected $prefix;
    
    //服务容器
    protected $container;
    
    protected $AUTH_ON;
    
    // 默认配置
    protected $_config = array(
        'AUTH_ON' => true, // 认证开关
        'AUTH_GROUP' => 'auth_group', // 用户组数据表名
        'AUTH_GROUP_ACCESS' => 'auth_group_access', // 用户-用户组关系表
        'AUTH_RULE' => 'auth_rule', // 权限规则表
        'AUTH_USER' => 'users'
    );

    public function __construct()
    {
        //权限开关
        $this->AUTH_ON = $this->get('core.common')->C('_auth_on');
    }

    /**
     * 检查权限，验证原则是：禁止一切未授权的
     *
     * @param
     *            name string|array 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param
     *            uid int 认证用户的id
     * @param
     *            string mode 执行check的模式
     * @param
     *            relation string 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean 通过验证返回true;失败返回false
     */
    public function check($name, UserInterface $user, $mode = 'url', $relation = 'or')
    {
        $param = "";
        if (! $this->AUTH_ON) return true;

        // 获取用户需要验证的所有有效规则列表
        $authList = $this->getAuthList($user);
        
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array(
                    $name
                );
            }
        }
        
        //保存验证通过的规则名
        $list = array(); 
        if ($mode == 'url') {
            $REQUEST = unserialize(strtolower(serialize($_REQUEST)));
        }

        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ($mode == 'url' && $query != $auth) {
                
                //解析规则中的param
                parse_str($query, $param);
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth = preg_replace('/\?.*$/U', '', $auth);
                
                // 如果节点相符且url参数满足
                if (in_array($auth, $name) && $intersect == $param)
                {
                    $list[] = $auth;
                }
            } elseif (in_array($auth, $name)) {
                    $list[] = $auth;
            }
        }
        if ($relation == 'or' and ! empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 检查权限，验证原则是：开放一切未禁止的，与上面的check方法刚好相反 weiphp 20150715
     *
     * @param
     *            name string|array 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param
     *            uid int 认证用户的id
     * @param
     *            string mode 执行check的模式
     * @param
     *            relation string 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean 通过验证返回true;失败返回false
     */
    public function checkRule($name = '', $uid = '', $mode = 'url', $relation = 'or')
    {
        $param = "";
        if (! $this->AUTH_ON) return true;
        
//         empty($uid) && $uid = session('mid');
//         if (C('USER_ADMINISTRATOR') == $uid)
//             return true;
        
        // 获取用户需要验证的所有禁止规则列表
        $authList = $this->getAuthList($uid, 'not in');
        
        $index_1 = strtolower(MODULE_NAME . '/*/*');
        $index_2 = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/*');
        $index_3 = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);
        $replace = array(
            strtolower(MODULE_NAME),
            strtolower(CONTROLLER_NAME),
            strtolower(ACTION_NAME)
        );
        if ($index_3 == 'home/addons/execute' || $index_3 == 'home/addons/plugin') {
            $index_1 = strtolower(_ADDONS . '/*/*');
            $index_2 = strtolower(_ADDONS . '/' . _CONTROLLER . '/*');
            $index_3 = strtolower(_ADDONS . '/' . _CONTROLLER . '/' . _ACTION);
            $replace = array(
                strtolower(_ADDONS),
                strtolower(_CONTROLLER),
                strtolower(_ACTION)
            );
        }
        
        if (empty($name)) {
            $name = array(
                $index_1,
                $index_2,
                $index_3
            );
        } elseif (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array(
                    $name
                );
            }
        }
        // 替换一些变量类的规则
        foreach ($name as &$n) {
            $n = str_replace(array(
                '__MODULE__',
                '__CONTROLLER__',
                '__ACTION__'
            ), $replace, $n);
        }
        
        $list = array(); // 保存验证通过的规则名
        if ($mode == 'url') {
            $REQUEST = unserialize(strtolower(serialize($_REQUEST)));
        }
        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ($mode == 'url' && $query != $auth) {
                parse_str($query, $param); // 解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) { // 如果节点相符且url参数满足
                    $list[] = $auth;
                }
            } else 
                if (in_array($auth, $name)) {
                    $list[] = $auth;
                }
        }
        if ($relation == 'or' and ! empty($list)) {
            return false;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return false;
        }
        return true;
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     *
     * @param       uid int 用户id
     * @return array 用户所属的用户组 array(
     *         array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *         ...)
     */
    public function getGroups($uid)
    {
        static $groups = array();
        if (isset($groups[$uid])) return $groups[$uid];
        
        $auth_group_access = $this->get('db.auth_access')->findBy(array('uid'=>$uid));
        
        if(empty($auth_group_access['data'])) return array();

        $user_groups = array();
        foreach($auth_group_access['data'] as $key=>$item)
        {
            $auth_group = $this->get('db.auth_group')->findOneBy(array('id'=>$item->getGroupId()), array(), false);
            $auth_group_status = is_object($auth_group)?(int)$auth_group->getStatus():0;
            
            if(is_object($auth_group)&&$auth_group_status==0)
            {
                $user_groups[$key]['uid'] = $uid;
                $user_groups[$key]['group_id'] = $item->getGroupId();
                $user_groups[$key]['name'] = $auth_group->getName();
                $user_groups[$key]['rules'] = $auth_group->getRules();
            }
        }

        $groups[$uid] = $user_groups?$user_groups:array();

        return $groups[$uid];
    }

    /**
     * 获得权限列表
     *
     * @param integer $uid 用户id
     * @param string  $type {in,notin}
     */
    protected function getAuthList(UserInterface $user, $type = 'in')
    {
        $uid = method_exists($user, 'getId')?$user->getId():0;
        $map = array();
        $condition = "";
        
        //保存用户验证通过的权限列表
        static $_authList = array(); 
        $t = $type == 'in' ? 'in' : 'notIn';
        if (isset($_authList[$uid . $t])) {
            return $_authList[$uid . $t];
        }
        
        // 读取用户所属用户组
        $groups = $this->getGroups($uid);
        
        $ids = array(); // 保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }

        $ids = array_unique($ids);

        if (empty($ids)) {
            $_authList[$uid . $t] = array();
            return array();
        }
        
        $map['id'] = array($type=>$ids);
        $map['status'] = 0;

        // 读取用户组所有权限规则
        $rules = $this->get('db.auth_rule')->findBy($map);
        
        // 循环规则，判断结果。
        $authList = array(); //
        foreach ($rules['data'] as $rule) {
            $condition = $rule->getCondition();
            // 根据condition进行验证
            if (! empty($condition))
            {
                $command = preg_replace('/\{(\w*?)\}/', '$user->get\\1()', $condition);

                
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = strtolower($rule->getIdentities());
                }
            } else {
                // 只要存在就记录
                $authList[] = strtolower($rule->getIdentities());
            }
        }
        $_authList[$uid . $t] = $authList;
        return array_unique($authList);
    }
}