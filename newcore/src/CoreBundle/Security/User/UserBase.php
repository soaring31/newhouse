<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace CoreBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * LdapUser is the user implementation used by the LDAP user provider.
 *
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class UserBase implements UserInterface
{
    protected $id;
    protected $mid;
    protected $cid;
    protected $tel;
    protected $salt;
    protected $email;
    protected $image;
    protected $userdb;
    protected $locale;
    protected $yongjin;
    protected $groupid;
    protected $oauthid;
    protected $usertype;
    protected $username;
    protected $nicename;
    protected $logintime;
    protected $usertplid;
    protected $onlinetime;
    protected $login_flag;
    protected $rules = array();
    protected $roles = array();
    protected $userinfo = array();
    protected $currency = array();       
    protected $rolename = "普通会员";    
    protected $rawToken = array();
    protected $resourceOwnerName;
    protected $attachRoles = array();

    /**
     * Constructor.
     *
     * @param string $username
     * @param array  $roles
     */
    public function __construct($user)
    {
        $this->id = $user->getId();
        $this->mid = method_exists($user, 'getMid')?$user->getMid():0;
        $this->cid = method_exists($user, 'getCid')?$user->getCid():0;
        $this->tel = method_exists($user, 'getTel')?$user->getTel():"";
        $this->salt = method_exists($user, 'getSalt')?$user->getSalt():"";
        $this->locale = method_exists($user, 'getLocale')?$user->getLocale():"";
        $this->email = method_exists($user, 'getEmail')?$user->getEmail():"";
        $this->userdb = method_exists($user, 'getUserdb')?$user->getUserdb():"";
        $this->yongjin = method_exists($user, 'getYongjin')?$user->getYongjin():0;
        $this->uptotal = method_exists($user, 'getUptotal')?$user->getUptotal():0;
        $this->usertype = method_exists($user, 'getUsertype')?$user->getUsertype():0;  
        $this->groupid = method_exists($user, 'getGroupid')?$user->getGroupid():0;
        $this->username = method_exists($user, 'getUsername')?$user->getUsername():"";
        $this->nicename = method_exists($user, 'getNicename')?$user->getNicename():"";
        $this->downtotal = method_exists($user, 'getDowntotal')?$user->getDowntotal():0;
        $this->usertplid = method_exists($user, 'getUsertplid')?$user->getUsertplid():"";
        $this->logintime = method_exists($user, 'getLogintime')?$user->getLogintime():0;
        $this->login_flag = method_exists($user, 'getLoginFlag')?$user->getLoginFlag():"";     
        $this->onlinetime = method_exists($user, 'getOnlinetime')?$user->getOnlinetime():0;
        $this->image = method_exists($user, 'getImage')?$user->getImage():"";
    }

    public function __toString()
    {
        return $this->username;
    }
    
    public function getRolename()
    {
        return $this->rolename;
    }
    
    public function setRolename($rolename)
    {
        $this->rolename = $rolename;
        
        return $this;
    }
    
    public function getRules()
    {
        return $this->rules;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    
        return $this;
    }
    
    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }
    
    public function getAttachRoles()
    {
        return $this->attachRoles;
    }
    
    public function setAttachRoles(array $attachRoles)
    {
        $this->attachRoles = $attachRoles;
    
        return $this;
    }
    
    public function getCid()
    {
        return $this->cid;
    }
    
    public function setCid($cid)
    {
        $this->cid = $cid;
    
        return $this;
    }
    
    public function getYongjin()
    {
        return $this->yongjin;
    }
    
    public function setYongjin($yongjin)
    {
        $this->yongjin = $yongjin;
        return $this;
    }
    
    public function getRawToken()
    {
        return $this->rawToken;
    }
    
    public function setRawToken($rawToken)
    {
        $this->rawToken = $rawToken;
        return $this;
    }
    
    public function getResourceOwnerName()
    {
        return $this->resourceOwnerName;
    }
    
    public function setResourceOwnerName($resourceOwnerName)
    {
        $this->resourceOwnerName = $resourceOwnerName;
        return $this;
    }
    
    public function getOauthId()
    {
        return $this->oauthid;
    }
    
    public function setOauthId($oauthid)
    {
        $this->oauthid = $oauthid;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getMid()
    {
        return $this->mid;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getUsertype()
    {
        return $this->usertype;
    }
    
    public function setUsertype($usertype)
    {
        $this->usertype = (int)$usertype;
    }
    
    public function getUserinfo()
    {
        return $this->userinfo;
    }
    
    public function setUserinfo($userinfo)
    {
        $this->userinfo = $userinfo;
    }
    
    public function getGroupid()
    {
        return $this->groupid;
    }
    
    public function setGroupid($groupid)
    {
        $this->groupid = (int)$groupid;
    }

    public function getNicename()
    {
        return $this->nicename;
    }
    
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getTel()
    {
        return $this->tel;
    }
    
    public function getUptotal()
    {
        return $this->uptotal;
    }

    public function getDowntotal()
    {
        return $this->downtotal;
    }

    public function getUsertplid()
    {
        return $this->usertplid;
    }
    
    public function setUsertplid($usertplid)
    {
        $this->usertplid = $usertplid;
        return $this;
    }

    public function getLoginFlag()
    {
        return $this->login_flag;
    }
    
    public function setLoginFlag($loginFlag)
    {
        $this->login_flag = $loginFlag;
    }

    public function getUserdb()
    {
        return $this->userdb;
    }

    public function getRoles(){
        return $this->roles;
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function setCurrency(array $currency)
    {
        $this->currency = $currency;
    }

    public function eraseCredentials(){}

    public function equals(UserInterface $account)
    {
        if (!$account instanceof User) {
            return false;
        }

        if ($this->login_flag !== $account->getLoginFlag()) {
            return false;
        }

        if ($this->username !== $account->getUsername()) {
            return false;
        }

        return true;
    }
}
