<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月20日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Security\User\User;
use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* oauth认证表
*
*/
class UserOauth extends AbstractServiceManager
{
    protected $table = 'UserOauth';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    /**
     * 第三方登陆处理
     * 
     * openid 普通用户的标识，对当前开发者帐号唯一。一个openid对应一个公众号。
     * unionid 用户统一标识。针对一个微信开放平台帐号下的应用，同一用户的unionid是唯一的。
     * 
     * 有一个地方需要注意的：
     * 即如果开发者有在公众号、移动应用之间统一用户帐号的需求，需要前往微信开放平台（open.weixin.qq.com）绑定公众号后，才可利用UnionID机制来满足上述需求。
     * 即我们在使用微信网页版本的时候，生成微信二维码用户扫描登录，此时获取的openid和前面我们所说通过微信客户端获取的openid就不是一样的，但是UnionID是一样的，
     * 这是因为，网页二维码扫描登录是网站应用，第一种获取openid是公众号，两者属于不同应用，所以是不一样的。
     * 所以判断是否为同一个微信用户时应该用其unionid进行判断
     * 
     * @param array $data
     * @param string $userInformation
     * @return boolean
     */
    public function loginHandle(array $data, $oauthToken=null)
    {
        $map = array();
        $map['type'] = $data['type'];
        $map['oauthid'] = $data['oauthid'];
        
        //获取unionid
        if (isset($data['extend']))
        {
            $extend = json_decode($data['extend'], true);
            if (isset($extend['unionid']))
                $data['unionid'] = $extend['unionid'];
        }

        $login = parent::findOneBy($map);

        if(!is_object($login))
        {
            // 根据unionid获取用户，因为一个用户可以有多个oauthid，但只有一个unionid，目的是为了打通用户
            if (isset($data['unionid']))
                $login2 = parent::findOneBy(array('unionid'=>$data['unionid']));
            
            register_user:

            $user = array();
            $user['username'] = md5($data['oauthid']);
            $user['nicename'] = $data['nickname'];
            $user['password'] = $this->get('core.common')->getRandStr(6);

            if (isset($login2) && is_object($login2))
            {
                //??是否用useroauth.uid去查询users.id更合适??
                $users = $this->get('db.users')->findOneBy(array('id'=>$login2->getUid()));
                 //如果已经有相同unionid的数据则将checked设置为1，目的是不让其再次执行绑定操作
                $data['checked'] = 1;
            } else {
                //检测是否之前有过帐号 
                $users = $this->get('db.users')->findOneBy(array('username'=>$user['username']));
            }

            //注册帐号并绑定
            $users = is_object($users)?$users:$this->get('db.users')->add($user, null, false);

            if(!is_object($users))
                return false;

            $data['uid'] = $users->getId();
            
            if(is_object($login))
            {
                $login->setUid($data['uid']);
                parent::update($login->getId(), $data, $login, false);
            }else
                $login = parent::add($data, null, false);

            $uid = $users->getId();
        }else{
            $uid = $login->getUid();
        }

        $info = $this->get('db.users')->findOneBy(array('id' => $uid));

        //如果绑定帐号已被删除则重新注册
        if(!is_object($info))
            goto register_user;

        $user = new User($info);
        $user->setOauthId($data['oauthid']);
        $user->setRawToken($oauthToken->getRawToken());
        $user->setResourceOwnerName($data['type']);        

        $this->get('core.rbac')->loginHandle($user);
        return $login;
    }
}