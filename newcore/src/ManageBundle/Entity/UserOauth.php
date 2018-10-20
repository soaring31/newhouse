<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserOauth
 *
 * @ORM\Table(name="user_oauth")
 * @ORM\Entity
 */
class UserOauth
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=100, nullable=false)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="realname", type="string", length=100, nullable=false)
     */
    private $realname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="extend", type="text", length=0, nullable=false)
     */
    private $extend;

    /**
     * @var string
     *
     * @ORM\Column(name="headimage", type="string", length=255, nullable=false)
     */
    private $headimage;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=64, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="oauthid", type="string", length=128, nullable=false)
     */
    private $oauthid;

    /**
     * @var string
     *
     * @ORM\Column(name="accessToken", type="string", length=100, nullable=false)
     */
    private $accessToken;

    /**
     * @var integer
     *
     * @ORM\Column(name="expiresIn", type="integer", length=10, nullable=false)
     */
    private $expiresIn;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenSecret", type="string", length=100, nullable=false)
     */
    private $tokenSecret;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=false)
     */
    private $checked;

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
     * Set type
     *
     * @param string $type
     * @return UserOauth
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return UserOauth
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    
        return $this;
    }

    /**
     * Get uid
     *
     * @return integer 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return UserOauth
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set realname
     *
     * @param string $realname
     * @return UserOauth
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;
    
        return $this;
    }

    /**
     * Get realname
     *
     * @return string 
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return UserOauth
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
     * Set extend
     *
     * @param string $extend
     * @return UserOauth
     */
    public function setExtend($extend)
    {
        $this->extend = $extend;
    
        return $this;
    }

    /**
     * Get extend
     *
     * @return string 
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * Set headimage
     *
     * @param string $headimage
     * @return UserOauth
     */
    public function setHeadimage($headimage)
    {
        $this->headimage = $headimage;
    
        return $this;
    }

    /**
     * Get headimage
     *
     * @return string 
     */
    public function getHeadimage()
    {
        return $this->headimage;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return UserOauth
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
     * Set oauthid
     *
     * @param string $oauthid
     * @return UserOauth
     */
    public function setOauthid($oauthid)
    {
        $this->oauthid = $oauthid;
    
        return $this;
    }

    /**
     * Get oauthid
     *
     * @return string 
     */
    public function getOauthid()
    {
        return $this->oauthid;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return UserOauth
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    
        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set expiresIn
     *
     * @param integer $expiresIn
     * @return UserOauth
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
    
        return $this;
    }

    /**
     * Get expiresIn
     *
     * @return integer 
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * Set tokenSecret
     *
     * @param string $tokenSecret
     * @return UserOauth
     */
    public function setTokenSecret($tokenSecret)
    {
        $this->tokenSecret = $tokenSecret;
    
        return $this;
    }

    /**
     * Get tokenSecret
     *
     * @return string 
     */
    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return UserOauth
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    
        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return UserOauth
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
     * @return UserOauth
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
     * @return UserOauth
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
     * @return UserOauth
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
     * @return UserOauth
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
     * @return UserOauth
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
     * @return UserOauth
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
