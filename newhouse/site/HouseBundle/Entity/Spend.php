<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Spend
 *
 * @ORM\Table(name="spend")
 * @ORM\Entity
 */
class Spend
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
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="consume", type="string", length=100, nullable=false)
     */
    private $consume;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", length=100, nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer", length=10, nullable=false)
     */
    private $value;

    /**
     * @var float
     *
     * @ORM\Column(name="cmoney", type="float", length=15, nullable=false)
     */
    private $cmoney;

    /**
     * @var float
     *
     * @ORM\Column(name="bmoney", type="float", length=100, nullable=false)
     */
    private $bmoney;

    /**
     * @var integer
     *
     * @ORM\Column(name="touid", type="integer", length=10, nullable=false)
     */
    private $touid;

    /**
     * @var string
     *
     * @ORM\Column(name="services", type="string", length=50, nullable=false)
     */
    private $services;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=10, nullable=false)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="balance", type="smallint", length=3, nullable=false)
     */
    private $balance;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", length=0, nullable=false)
     */
    private $remark;

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
     * @return Spend
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
     * Set consume
     *
     * @param string $consume
     * @return Spend
     */
    public function setConsume($consume)
    {
        $this->consume = $consume;
    
        return $this;
    }

    /**
     * Get consume
     *
     * @return string 
     */
    public function getConsume()
    {
        return $this->consume;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Spend
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set value
     *
     * @param integer $value
     * @return Spend
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set cmoney
     *
     * @param float $cmoney
     * @return Spend
     */
    public function setCmoney($cmoney)
    {
        $this->cmoney = $cmoney;
    
        return $this;
    }

    /**
     * Get cmoney
     *
     * @return float 
     */
    public function getCmoney()
    {
        return $this->cmoney;
    }

    /**
     * Set bmoney
     *
     * @param float $bmoney
     * @return Spend
     */
    public function setBmoney($bmoney)
    {
        $this->bmoney = $bmoney;
    
        return $this;
    }

    /**
     * Get bmoney
     *
     * @return float 
     */
    public function getBmoney()
    {
        return $this->bmoney;
    }

    /**
     * Set touid
     *
     * @param integer $touid
     * @return Spend
     */
    public function setTouid($touid)
    {
        $this->touid = $touid;
    
        return $this;
    }

    /**
     * Get touid
     *
     * @return integer 
     */
    public function getTouid()
    {
        return $this->touid;
    }

    /**
     * Set services
     *
     * @param string $services
     * @return Spend
     */
    public function setServices($services)
    {
        $this->services = $services;
    
        return $this;
    }

    /**
     * Get services
     *
     * @return string 
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set aid
     *
     * @param integer $aid
     * @return Spend
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
     * Set name
     *
     * @param string $name
     * @return Spend
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
     * Set balance
     *
     * @param integer $balance
     * @return Spend
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    
        return $this;
    }

    /**
     * Get balance
     *
     * @return integer 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Spend
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
     * Set checked
     *
     * @param boolean $checked
     * @return Spend
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
     * @return Spend
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
     * @return Spend
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
     * @return Spend
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
     * @return Spend
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
     * @return Spend
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
     * @return Spend
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
     * @return Spend
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
