<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxusers
 *
 * @ORM\Table(name="wxusers")
 * @ORM\Entity
 */
class Wxusers
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
     * @ORM\Column(name="usemodel", type="string", length=100, nullable=false)
     */
    private $usemodel;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", length=100, nullable=false)
     */
    private $sid;

    /**
     * @var string
     *
     * @ORM\Column(name="wxaccount", type="string", length=50, nullable=false)
     */
    private $wxaccount;

    /**
     * @var string
     *
     * @ORM\Column(name="appid", type="string", length=100, nullable=false)
     */
    private $appid;

    /**
     * @var string
     *
     * @ORM\Column(name="appsecret", type="string", length=100, nullable=false)
     */
    private $appsecret;

    /**
     * @var integer
     *
     * @ORM\Column(name="auth_type", type="smallint", length=2, nullable=false)
     */
    private $auth_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="encode", type="smallint", length=2, nullable=false)
     */
    private $encode;

    /**
     * @var string
     *
     * @ORM\Column(name="easkey", type="string", length=100, nullable=false)
     */
    private $easkey;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=false)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="actoken", type="string", length=255, nullable=false)
     */
    private $actoken;

    /**
     * @var integer
     *
     * @ORM\Column(name="acexp", type="integer", length=10, nullable=false)
     */
    private $acexp;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", length=3, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="component_verify_ticket", type="string", length=100, nullable=false)
     */
    private $component_verify_ticket;

    /**
     * @var string
     *
     * @ORM\Column(name="component_access_token", type="string", length=100, nullable=false)
     */
    private $component_access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="mchid", type="string", length=50, nullable=false)
     */
    private $mchid;

    /**
     * @var integer
     *
     * @ORM\Column(name="pushs", type="integer", length=10, nullable=false)
     */
    private $pushs;

    /**
     * @var string
     *
     * @ORM\Column(name="paysignkey", type="string", length=100, nullable=false)
     */
    private $paysignkey;

    /**
     * @var string
     *
     * @ORM\Column(name="wxplan", type="string", length=10, nullable=false)
     */
    private $wxplan;

    /**
     * @var string
     *
     * @ORM\Column(name="aid", type="string", length=100, nullable=false)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var string
     *
     * @ORM\Column(name="checked", type="string", length=100, nullable=false)
     */
    private $checked = '1';

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
     * Set usemodel
     *
     * @param string $usemodel
     * @return Wxusers
     */
    public function setUsemodel($usemodel)
    {
        $this->usemodel = $usemodel;
    
        return $this;
    }

    /**
     * Get usemodel
     *
     * @return string 
     */
    public function getUsemodel()
    {
        return $this->usemodel;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Wxusers
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sid
     *
     * @param string $sid
     * @return Wxusers
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    
        return $this;
    }

    /**
     * Get sid
     *
     * @return string 
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set wxaccount
     *
     * @param string $wxaccount
     * @return Wxusers
     */
    public function setWxaccount($wxaccount)
    {
        $this->wxaccount = $wxaccount;
    
        return $this;
    }

    /**
     * Get wxaccount
     *
     * @return string 
     */
    public function getWxaccount()
    {
        return $this->wxaccount;
    }

    /**
     * Set appid
     *
     * @param string $appid
     * @return Wxusers
     */
    public function setAppid($appid)
    {
        $this->appid = $appid;
    
        return $this;
    }

    /**
     * Get appid
     *
     * @return string 
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * Set appsecret
     *
     * @param string $appsecret
     * @return Wxusers
     */
    public function setAppsecret($appsecret)
    {
        $this->appsecret = $appsecret;
    
        return $this;
    }

    /**
     * Get appsecret
     *
     * @return string 
     */
    public function getAppsecret()
    {
        return $this->appsecret;
    }

    /**
     * Set auth_type
     *
     * @param integer $authType
     * @return Wxusers
     */
    public function setAuthType($authType)
    {
        $this->auth_type = $authType;
    
        return $this;
    }

    /**
     * Get auth_type
     *
     * @return integer 
     */
    public function getAuthType()
    {
        return $this->auth_type;
    }

    /**
     * Set encode
     *
     * @param integer $encode
     * @return Wxusers
     */
    public function setEncode($encode)
    {
        $this->encode = $encode;
    
        return $this;
    }

    /**
     * Get encode
     *
     * @return integer 
     */
    public function getEncode()
    {
        return $this->encode;
    }

    /**
     * Set easkey
     *
     * @param string $easkey
     * @return Wxusers
     */
    public function setEaskey($easkey)
    {
        $this->easkey = $easkey;
    
        return $this;
    }

    /**
     * Get easkey
     *
     * @return string 
     */
    public function getEaskey()
    {
        return $this->easkey;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Wxusers
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
     * Set actoken
     *
     * @param string $actoken
     * @return Wxusers
     */
    public function setActoken($actoken)
    {
        $this->actoken = $actoken;
    
        return $this;
    }

    /**
     * Get actoken
     *
     * @return string 
     */
    public function getActoken()
    {
        return $this->actoken;
    }

    /**
     * Set acexp
     *
     * @param integer $acexp
     * @return Wxusers
     */
    public function setAcexp($acexp)
    {
        $this->acexp = $acexp;
    
        return $this;
    }

    /**
     * Get acexp
     *
     * @return integer 
     */
    public function getAcexp()
    {
        return $this->acexp;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Wxusers
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
     * Set type
     *
     * @param integer $type
     * @return Wxusers
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set component_verify_ticket
     *
     * @param string $componentVerifyTicket
     * @return Wxusers
     */
    public function setComponentVerifyTicket($componentVerifyTicket)
    {
        $this->component_verify_ticket = $componentVerifyTicket;
    
        return $this;
    }

    /**
     * Get component_verify_ticket
     *
     * @return string 
     */
    public function getComponentVerifyTicket()
    {
        return $this->component_verify_ticket;
    }

    /**
     * Set component_access_token
     *
     * @param string $componentAccessToken
     * @return Wxusers
     */
    public function setComponentAccessToken($componentAccessToken)
    {
        $this->component_access_token = $componentAccessToken;
    
        return $this;
    }

    /**
     * Get component_access_token
     *
     * @return string 
     */
    public function getComponentAccessToken()
    {
        return $this->component_access_token;
    }

    /**
     * Set mchid
     *
     * @param string $mchid
     * @return Wxusers
     */
    public function setMchid($mchid)
    {
        $this->mchid = $mchid;
    
        return $this;
    }

    /**
     * Get mchid
     *
     * @return string 
     */
    public function getMchid()
    {
        return $this->mchid;
    }

    /**
     * Set pushs
     *
     * @param integer $pushs
     * @return Wxusers
     */
    public function setPushs($pushs)
    {
        $this->pushs = $pushs;
    
        return $this;
    }

    /**
     * Get pushs
     *
     * @return integer 
     */
    public function getPushs()
    {
        return $this->pushs;
    }

    /**
     * Set paysignkey
     *
     * @param string $paysignkey
     * @return Wxusers
     */
    public function setPaysignkey($paysignkey)
    {
        $this->paysignkey = $paysignkey;
    
        return $this;
    }

    /**
     * Get paysignkey
     *
     * @return string 
     */
    public function getPaysignkey()
    {
        return $this->paysignkey;
    }

    /**
     * Set wxplan
     *
     * @param string $wxplan
     * @return Wxusers
     */
    public function setWxplan($wxplan)
    {
        $this->wxplan = $wxplan;
    
        return $this;
    }

    /**
     * Get wxplan
     *
     * @return string 
     */
    public function getWxplan()
    {
        return $this->wxplan;
    }

    /**
     * Set aid
     *
     * @param string $aid
     * @return Wxusers
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    
        return $this;
    }

    /**
     * Get aid
     *
     * @return string 
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set models
     *
     * @param string $models
     * @return Wxusers
     */
    public function setModels($models)
    {
        $this->models = $models;
    
        return $this;
    }

    /**
     * Get models
     *
     * @return string 
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Set checked
     *
     * @param string $checked
     * @return Wxusers
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    
        return $this;
    }

    /**
     * Get checked
     *
     * @return string 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Wxusers
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
     * @return Wxusers
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
     * @return Wxusers
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
     * @return Wxusers
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
     * @return Wxusers
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
     * @return Wxusers
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
     * @return Wxusers
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
