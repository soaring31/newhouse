<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userpay
 *
 * @ORM\Table(name="userpay")
 * @ORM\Entity
 */
class Userpay
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", length=2, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", length=10, nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="pay", type="smallint", length=2, nullable=false)
     */
    private $pay;

    /**
     * @var integer
     *
     * @ORM\Column(name="orders", type="integer", length=10, nullable=false)
     */
    private $orders;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ddate", type="time", length=100, nullable=false)
     */
    private $ddate;

    /**
     * @var string
     *
     * @ORM\Column(name="xingming", type="string", length=100, nullable=false)
     */
    private $xingming;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="string", length=100, nullable=false)
     */
    private $remarks;

    /**
     * @var string
     *
     * @ORM\Column(name="cert", type="text", length=0, nullable=false)
     */
    private $cert;

    /**
     * @var integer
     *
     * @ORM\Column(name="arrives", type="smallint", length=2, nullable=false)
     */
    private $arrives;

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
     * @return Userpay
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
     * Set name
     *
     * @param string $name
     * @return Userpay
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
     * Set type
     *
     * @param integer $type
     * @return Userpay
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
     * Set amount
     *
     * @param integer $amount
     * @return Userpay
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set pay
     *
     * @param integer $pay
     * @return Userpay
     */
    public function setPay($pay)
    {
        $this->pay = $pay;
    
        return $this;
    }

    /**
     * Get pay
     *
     * @return integer 
     */
    public function getPay()
    {
        return $this->pay;
    }

    /**
     * Set orders
     *
     * @param integer $orders
     * @return Userpay
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    
        return $this;
    }

    /**
     * Get orders
     *
     * @return integer 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set ddate
     *
     * @param \DateTime $ddate
     * @return Userpay
     */
    public function setDdate($ddate)
    {
        $this->ddate = $ddate;
    
        return $this;
    }

    /**
     * Get ddate
     *
     * @return \DateTime 
     */
    public function getDdate()
    {
        return $this->ddate;
    }

    /**
     * Set xingming
     *
     * @param string $xingming
     * @return Userpay
     */
    public function setXingming($xingming)
    {
        $this->xingming = $xingming;
    
        return $this;
    }

    /**
     * Get xingming
     *
     * @return string 
     */
    public function getXingming()
    {
        return $this->xingming;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Userpay
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
     * Set email
     *
     * @param string $email
     * @return Userpay
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
     * Set remarks
     *
     * @param string $remarks
     * @return Userpay
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    
        return $this;
    }

    /**
     * Get remarks
     *
     * @return string 
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set cert
     *
     * @param string $cert
     * @return Userpay
     */
    public function setCert($cert)
    {
        $this->cert = $cert;
    
        return $this;
    }

    /**
     * Get cert
     *
     * @return string 
     */
    public function getCert()
    {
        return $this->cert;
    }

    /**
     * Set arrives
     *
     * @param integer $arrives
     * @return Userpay
     */
    public function setArrives($arrives)
    {
        $this->arrives = $arrives;
    
        return $this;
    }

    /**
     * Get arrives
     *
     * @return integer 
     */
    public function getArrives()
    {
        return $this->arrives;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Userpay
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
     * @return Userpay
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
     * @return Userpay
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
     * @return Userpay
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
     * @return Userpay
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
     * @return Userpay
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
     * @return Userpay
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
     * @return Userpay
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
