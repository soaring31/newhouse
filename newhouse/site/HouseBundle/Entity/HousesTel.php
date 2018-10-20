<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HousesTel
 *
 * @ORM\Table(name="houses_tel", indexes={@ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="broker_id", columns={"broker_id"}), @ORM\Index(name="area", columns={"area"}), @ORM\Index(name="aid", columns={"aid"})})
 * @ORM\Entity
 */
class HousesTel
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
     * @ORM\Column(name="uid", type="integer", length=11, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="broker_id", type="integer", length=11, nullable=false)
     */
    private $broker_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=11, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=11, nullable=false)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="broker_username", type="string", length=100, nullable=false)
     */
    private $broker_username;

    /**
     * @var string
     *
     * @ORM\Column(name="broker_real_name", type="string", length=50, nullable=false)
     */
    private $broker_real_name;

    /**
     * @var integer
     *
     * @ORM\Column(name="platform_type", type="smallint", length=1, nullable=false)
     */
    private $platform_type;

    /**
     * @var string
     *
     * @ORM\Column(name="spread", type="string", length=100, nullable=false)
     */
    private $spread;

    /**
     * @var string
     *
     * @ORM\Column(name="virtualPhone", type="string", length=100, nullable=false)
     */
    private $virtualPhone;

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
     * Set uid
     *
     * @param integer $uid
     * @return HousesTel
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
     * Set broker_id
     *
     * @param integer $brokerId
     * @return HousesTel
     */
    public function setBrokerId($brokerId)
    {
        $this->broker_id = $brokerId;
    
        return $this;
    }

    /**
     * Get broker_id
     *
     * @return integer 
     */
    public function getBrokerId()
    {
        return $this->broker_id;
    }

    /**
     * Set area
     *
     * @param integer $area
     * @return HousesTel
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return integer 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set aid
     *
     * @param integer $aid
     * @return HousesTel
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    
        return $this;
    }

    /**
     * Get aid
     *
     * @return integer 
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return HousesTel
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
     * Set broker_username
     *
     * @param string $brokerUsername
     * @return HousesTel
     */
    public function setBrokerUsername($brokerUsername)
    {
        $this->broker_username = $brokerUsername;
    
        return $this;
    }

    /**
     * Get broker_username
     *
     * @return string 
     */
    public function getBrokerUsername()
    {
        return $this->broker_username;
    }

    /**
     * Set broker_real_name
     *
     * @param string $brokerRealName
     * @return HousesTel
     */
    public function setBrokerRealName($brokerRealName)
    {
        $this->broker_real_name = $brokerRealName;
    
        return $this;
    }

    /**
     * Get broker_real_name
     *
     * @return string 
     */
    public function getBrokerRealName()
    {
        return $this->broker_real_name;
    }

    /**
     * Set platform_type
     *
     * @param integer $platformType
     * @return HousesTel
     */
    public function setPlatformType($platformType)
    {
        $this->platform_type = $platformType;
    
        return $this;
    }

    /**
     * Get platform_type
     *
     * @return integer 
     */
    public function getPlatformType()
    {
        return $this->platform_type;
    }

    /**
     * Set spread
     *
     * @param string $spread
     * @return HousesTel
     */
    public function setSpread($spread)
    {
        $this->spread = $spread;
    
        return $this;
    }

    /**
     * Get spread
     *
     * @return string 
     */
    public function getSpread()
    {
        return $this->spread;
    }

    /**
     * Set virtualPhone
     *
     * @param string $virtualPhone
     * @return HousesTel
     */
    public function setVirtualPhone($virtualPhone)
    {
        $this->virtualPhone = $virtualPhone;
    
        return $this;
    }

    /**
     * Get virtualPhone
     *
     * @return string 
     */
    public function getVirtualPhone()
    {
        return $this->virtualPhone;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return HousesTel
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
     * @return HousesTel
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
     * @return HousesTel
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
     * @return HousesTel
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
     * @return HousesTel
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
     * @return HousesTel
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
     * @return HousesTel
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
     * @return HousesTel
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
