<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace CoreBundle\Security\User;

use CoreBundle\Services\ServiceBase;
use OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * LdapUserProvider is an LDAP-based user provider.
 *
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class UserProvider extends ServiceBase implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /**
     * Constructor.
     *
     * @param LdapUserManagerInterface $ldapUserManager LDAP user manager instance
     * @param string                   $rolePrefix      Prefix for transforming group names to roles
     * @param array                    $defaultRoles    Default roles given to all users
     */
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}A553D3B32B8E92F69145F208F36956D3
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $map = array();
        $map['type'] = $response->getResourceOwner()->getName();
        $map['identifier'] = $response->getUsername();
        $useroauth = $this->get('db.user_oauth')->findOneBy($map, array(), false);

        //如果不存在，则新增
        if(empty($useroauth)||$useroauth->getUid()<=0)
        {
            $data = array();
            $info = array();

            $info['password'] = substr(sha1(uniqid(mt_rand(),1)), 0, 10);
            $info['nicename'] = $response->getNickname();
            
            $user = $this->get('db.users')->add($info, null, false);

            $data['uid'] = $user->getId();
            
            if(empty($useroauth))
            {                
                $data['identifier'] = $response->getUsername();
                $data['type'] = $response->getResourceOwner()->getName();
                $data['nickname'] = $response->getNickname();
                $data['realname'] = $response->getRealname();
                $data['profilepicture'] = $response->getProfilepicture();
                $data['firstName'] = $response->getFirstName();
                $data['lastname'] = $response->getLastname();
                $data['email'] = $response->getEmail();
                
                $this->get('db.user_oauth')->add($data);
            }else
                $this->get('db.user_oauth')->update($useroauth->getId(), $data);
        }else
            $user = $this->get('db.users')->findOneBy(array('id'=>$useroauth->getUid()), array(), false);

        return self::getRolesForUser($user);
    }
    
    public function loadUser($username)
    {
        return $this->get('core.user_manager')->findUserByUsernameOrEmail($username);
    }


    /**
     * @see Symfony\Component\Security\Core\User\UserProviderInterface::loadUserByUsername() 821320 821321
     */
    public function loadUserByUsername($username)
    {
        $user = $this->get('core.user_manager')->findUserByUsernameOrEmail($username);
        
        return self::getRolesForUser($user);
    }

    public function refreshUser(UserInterface $account)
    {
        if(!$account instanceof User)
            throw new UnsupportedUserException('Username was reloaded froms user provider.');

        $this->get('core.user_manager')->setUserDB(self::_getUserDb($account->getUserdb()));

        $refreshedUser = self::getRolesForUser($this->get('core.user_manager')->refreshUser($account));

        $refreshedUser->setRawToken($account->getRawToken());
        $refreshedUser->setOauthId($account->getOauthId());
        $refreshedUser->setResourceOwnerName($account->getResourceOwnerName());

        if($refreshedUser->getSalt()!=$account->getSalt())
            throw new \RuntimeException(sprintf('用户"%s"密码已修改，请重新登入.', $refreshedUser->getUsername()));

        if($refreshedUser->equals($account)===false&&(int)$this->get('core.common')->C('multip')==0)
            throw new \RuntimeException(sprintf('用户"%s"异常登出，请重新登入.', $refreshedUser->getUsername()));
        
        return $refreshedUser;
    }

    /**
     * Gets roles for the username.
     *
     * @param string $username
     * @return array
     */
    public function getRolesForUser($user)
    {
        $user = new User($user);

        $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;
        
        //用户属性表
        $userInfo = $this->get('db.userinfo')->createOneIfNone(array('uid'=>$user->getId()));
        
        $user->setCid($userInfo->getCid());
        $user->setUsertype($userInfo->getUsertype());
        $user->setGroupid($userInfo->getGroupid());
        $user->setNicename($userInfo->getNicename());
        $user->setImage($userInfo->getImage());
        $user->setUsertplid($userInfo->getUsertplid());
        
        $user->setUserinfo($userInfo);
        
        //取货币配置
        $map = array();
        $map['_multi'] = false;
        $integral = $this->get('db.integral')->findBy($map);
        
        if(isset($integral['data']))
        {
            //积分属性
            $map['mid'] = $userInfo->getId();
            $map['name']['orX'] = array();
            
            foreach($integral['data'] as $item)
            {
                if($item->getEname())
                    $map['name']['orX'][]['name'] = $item->getEname();
            }
            
            //货币赋值
            if($map['name']['orX'])
            {            
                $userAttr = $this->get('db.user_attr')->findBy($map, array(), 10);
                
                if(isset($userAttr['data']))
                {
                    $currency = array();
                    foreach( $userAttr['data'] as $attr)
                    {
                        $currency[$attr->getName()] = doubleval($attr->getValue());
                    }
                    $user->setCurrency($currency);
                }
            }
        }

        //创始人跳过检查权限
        if($mid==1)
        {
            $user->setRolename("系统创始人");
            return $user;
        }

        //获取入口的角色
        $groupIds = $this->get('core.rbac')->getAuthGroupAccess($user->getId());
        
        $user->setAttachRoles($this->get('core.rbac')->getAttachRoles());
        
        $map = array();
        $map['_multi'] = false;
        $map['id'] = array('in'=>$groupIds?$groupIds:array(0));
        $map = $this->get('core.rbac')->attachAreaToAuthGroupMap($map);
        
        $order = array();
        $order['sort'] = "asc";

        //访问组
        $authGroup = $this->get('db.auth_group')->findBy($map, $order);
        
        $authGroup = isset($authGroup['data'])?$authGroup['data']:array();

        $this->get('core.rbac')->inherits($authGroup);

        //检查权限
        $user->setRules($this->get('core.rbac')->getAuthRules($this->get('core.rbac')->getAuthGroups()));
        
        $user->setRolename($this->get('core.rbac')->getRolename());
        
        $user->setUsertplid($user->getUsertplid()?$user->getUsertplid():$this->get('core.rbac')->getUsertplid());

        return $user;
    }
    
    private function _getUserDb($userBundle)
    {
        $userBundle = $userBundle?$userBundle:$this->get('core.common')->getUserBundle();
        //去掉bundle后缀
        if (preg_match('/bundle$/', $userBundle))
            $userBundle = substr($userBundle, 0, -6);
    
        return $userBundle;
    }

    /**
     * @see Symfony\Component\Security\Core\User\UserProviderInterface::supportsClass()
     * @codeCoverageIgnore
     */
    public function supportsClass($class)
    {
        return $class === 'CoreBundle\Security\User\User';
    }
}
