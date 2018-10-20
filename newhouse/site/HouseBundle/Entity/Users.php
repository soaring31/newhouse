<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", indexes={@ORM\Index(name="username", columns={"username"}), @ORM\Index(name="email", columns={"email"}), @ORM\Index(name="tel", columns={"tel"}), @ORM\Index(name="city", columns={"city"})})
 * @ORM\Entity
 */
class Users
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="mid", type="integer", length=10, nullable=false)
     */
    private $mid;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=10, nullable=false)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", length=1, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=15, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=100, nullable=false)
     */
    private $remark;

    /**
     * @var integer
     *
     * @ORM\Column(name="onlinetime", type="integer", length=10, nullable=false)
     */
    private $onlinetime;

    /**
     * @var string
     *
     * @ORM\Column(name="login_flag", type="string", length=10, nullable=false)
     */
    private $login_flag;

    /**
     * @var integer
     *
     * @ORM\Column(name="uptotal", type="integer", length=10, nullable=false)
     */
    private $uptotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="downtotal", type="integer", length=10, nullable=false)
     */
    private $downtotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="logintime", type="integer", length=10, nullable=false)
     */
    private $logintime;

    /**
     * @var string
     *
     * @ORM\Column(name="loginip", type="string", length=20, nullable=false)
     */
    private $loginip;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=false)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=20, nullable=false)
     */
    private $locale;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="userdb", type="string", length=100, nullable=false)
     */
    private $userdb;

    /**
     * @var integer
     *
     * @ORM\Column(name="oid", type="integer", length=10, nullable=false)
     */
    private $oid;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=10, nullable=false)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", length=10, nullable=false)
     */
    private $city_id;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes", type="string", length=10, nullable=false)
     */
    private $attributes;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", nullable=false)
     */
    private $issystem;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=100, nullable=false)
     */
    private $identifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", length=10, nullable=false)
     */
    private $create_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="update_time", type="integer", length=10, nullable=false)
     */
    private $update_time;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", length=1, nullable=false)
     */
    private $is_delete;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mid
     *
     * @param integer $mid
     * @return Users
     */
    public function setMid($mid)
    {
        $this->mid = $mid;

        return $this;
    }

    /**
     * Get mid
     *
     * @return integer 
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Users
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Users
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Users
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string 
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set onlinetime
     *
     * @param integer $onlinetime
     * @return Users
     */
    public function setOnlinetime($onlinetime)
    {
        $this->onlinetime = $onlinetime;

        return $this;
    }

    /**
     * Get onlinetime
     *
     * @return integer 
     */
    public function getOnlinetime()
    {
        return $this->onlinetime;
    }

    /**
     * Set login_flag
     *
     * @param string $loginFlag
     * @return Users
     */
    public function setLoginFlag($loginFlag)
    {
        $this->login_flag = $loginFlag;

        return $this;
    }

    /**
     * Get login_flag
     *
     * @return string 
     */
    public function getLoginFlag()
    {
        return $this->login_flag;
    }

    /**
     * Set uptotal
     *
     * @param integer $uptotal
     * @return Users
     */
    public function setUptotal($uptotal)
    {
        $this->uptotal = $uptotal;

        return $this;
    }

    /**
     * Get uptotal
     *
     * @return integer 
     */
    public function getUptotal()
    {
        return $this->uptotal;
    }

    /**
     * Set downtotal
     *
     * @param integer $downtotal
     * @return Users
     */
    public function setDowntotal($downtotal)
    {
        $this->downtotal = $downtotal;

        return $this;
    }

    /**
     * Get downtotal
     *
     * @return integer 
     */
    public function getDowntotal()
    {
        return $this->downtotal;
    }

    /**
     * Set logintime
     *
     * @param integer $logintime
     * @return Users
     */
    public function setLogintime($logintime)
    {
        $this->logintime = $logintime;

        return $this;
    }

    /**
     * Get logintime
     *
     * @return integer 
     */
    public function getLogintime()
    {
        return $this->logintime;
    }

    /**
     * Set loginip
     *
     * @param string $loginip
     * @return Users
     */
    public function setLoginip($loginip)
    {
        $this->loginip = $loginip;

        return $this;
    }

    /**
     * Get loginip
     *
     * @return string 
     */
    public function getLoginip()
    {
        return $this->loginip;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Users
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Users
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return Users
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Users
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return integer 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set userdb
     *
     * @param string $userdb
     * @return Users
     */
    public function setUserdb($userdb)
    {
        $this->userdb = $userdb;

        return $this;
    }

    /**
     * Get userdb
     *
     * @return string 
     */
    public function getUserdb()
    {
        return $this->userdb;
    }

    /**
     * Set oid
     *
     * @param integer $oid
     * @return Users
     */
    public function setOid($oid)
    {
        $this->oid = $oid;

        return $this;
    }

    /**
     * Get oid
     *
     * @return integer 
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Users
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city_id
     *
     * @param integer $cityId
     * @return Users
     */
    public function setCityId($cityId)
    {
        $this->city_id = $cityId;

        return $this;
    }

    /**
     * Get city_id
     *
     * @return integer 
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Users
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes
     *
     * @return string 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Users
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return Users
     */
    public function setIssystem($issystem)
    {
        $this->issystem = $issystem;

        return $this;
    }

    /**
     * Get issystem
     *
     * @return boolean 
     */
    public function getIssystem()
    {
        return $this->issystem;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return Users
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set create_time
     *
     * @param integer $createTime
     * @return Users
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;

        return $this;
    }

    /**
     * Get create_time
     *
     * @return integer 
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set update_time
     *
     * @param integer $updateTime
     * @return Users
     */
    public function setUpdateTime($updateTime)
    {
        $this->update_time = $updateTime;

        return $this;
    }

    /**
     * Get update_time
     *
     * @return integer 
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set is_delete
     *
     * @param boolean $isDelete
     * @return Users
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get is_delete
     *
     * @return boolean 
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}
