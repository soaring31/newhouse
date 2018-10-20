<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orderlist
 *
 * @ORM\Table(name="orderlist", indexes={@ORM\Index(name="ordersn", columns={"ordersn"}), @ORM\Index(name="paytype", columns={"paytype"})})
 * @ORM\Entity
 */
class Orderlist
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
     * @ORM\Column(name="ordersn", type="string", length=30, nullable=false)
     */
    private $ordersn;

    /**
     * @var integer
     *
     * @ORM\Column(name="paytype", type="smallint", length=3, nullable=false)
     */
    private $paytype;

    /**
     * @var float
     *
     * @ORM\Column(name="total_fee", type="float", length=12, nullable=false)
     */
    private $total_fee;

    /**
     * @var string
     *
     * @ORM\Column(name="trade_status", type="string", length=100, nullable=false)
     */
    private $trade_status;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", length=1, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="conditions", type="text", length=0, nullable=false)
     */
    private $conditions;

    /**
     * @var string
     *
     * @ORM\Column(name="notify_time", type="string", length=50, nullable=false)
     */
    private $notify_time;

    /**
     * @var string
     *
     * @ORM\Column(name="trade_no", type="string", length=30, nullable=false)
     */
    private $trade_no;

    /**
     * @var string
     *
     * @ORM\Column(name="buyer_email", type="string", length=100, nullable=false)
     */
    private $buyer_email;

    /**
     * @var integer
     *
     * @ORM\Column(name="paying", type="smallint", length=2, nullable=false)
     */
    private $paying;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

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
     * Set ordersn
     *
     * @param string $ordersn
     * @return Orderlist
     */
    public function setOrdersn($ordersn)
    {
        $this->ordersn = $ordersn;
    
        return $this;
    }

    /**
     * Get ordersn
     *
     * @return string 
     */
    public function getOrdersn()
    {
        return $this->ordersn;
    }

    /**
     * Set paytype
     *
     * @param integer $paytype
     * @return Orderlist
     */
    public function setPaytype($paytype)
    {
        $this->paytype = $paytype;
    
        return $this;
    }

    /**
     * Get paytype
     *
     * @return integer 
     */
    public function getPaytype()
    {
        return $this->paytype;
    }

    /**
     * Set total_fee
     *
     * @param float $totalFee
     * @return Orderlist
     */
    public function setTotalFee($totalFee)
    {
        $this->total_fee = $totalFee;
    
        return $this;
    }

    /**
     * Get total_fee
     *
     * @return float 
     */
    public function getTotalFee()
    {
        return $this->total_fee;
    }

    /**
     * Set trade_status
     *
     * @param string $tradeStatus
     * @return Orderlist
     */
    public function setTradeStatus($tradeStatus)
    {
        $this->trade_status = $tradeStatus;
    
        return $this;
    }

    /**
     * Get trade_status
     *
     * @return string 
     */
    public function getTradeStatus()
    {
        return $this->trade_status;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Orderlist
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set conditions
     *
     * @param string $conditions
     * @return Orderlist
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    
        return $this;
    }

    /**
     * Get conditions
     *
     * @return string 
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Set notify_time
     *
     * @param string $notifyTime
     * @return Orderlist
     */
    public function setNotifyTime($notifyTime)
    {
        $this->notify_time = $notifyTime;
    
        return $this;
    }

    /**
     * Get notify_time
     *
     * @return string 
     */
    public function getNotifyTime()
    {
        return $this->notify_time;
    }

    /**
     * Set trade_no
     *
     * @param string $tradeNo
     * @return Orderlist
     */
    public function setTradeNo($tradeNo)
    {
        $this->trade_no = $tradeNo;
    
        return $this;
    }

    /**
     * Get trade_no
     *
     * @return string 
     */
    public function getTradeNo()
    {
        return $this->trade_no;
    }

    /**
     * Set buyer_email
     *
     * @param string $buyerEmail
     * @return Orderlist
     */
    public function setBuyerEmail($buyerEmail)
    {
        $this->buyer_email = $buyerEmail;
    
        return $this;
    }

    /**
     * Get buyer_email
     *
     * @return string 
     */
    public function getBuyerEmail()
    {
        return $this->buyer_email;
    }

    /**
     * Set paying
     *
     * @param integer $paying
     * @return Orderlist
     */
    public function setPaying($paying)
    {
        $this->paying = $paying;
    
        return $this;
    }

    /**
     * Get paying
     *
     * @return integer 
     */
    public function getPaying()
    {
        return $this->paying;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Orderlist
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
     * Set checked
     *
     * @param boolean $checked
     * @return Orderlist
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
     * @return Orderlist
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
     * @return Orderlist
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
     * @return Orderlist
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
     * @return Orderlist
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
     * @return Orderlist
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
     * @return Orderlist
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
     * @return Orderlist
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
