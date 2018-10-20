<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxdefault
 *
 * @ORM\Table(name="wxdefault")
 * @ORM\Entity
 */
class Wxdefault
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
     * @ORM\Column(name="token", type="string", length=200, nullable=false)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="reply_default", type="text", length=0, nullable=false)
     */
    private $reply_default;

    /**
     * @var string
     *
     * @ORM\Column(name="subscribe_default", type="text", length=0, nullable=false)
     */
    private $subscribe_default;

    /**
     * @var string
     *
     * @ORM\Column(name="unsubscribe_default", type="text", length=0, nullable=false)
     */
    private $unsubscribe_default;

    /**
     * @var string
     *
     * @ORM\Column(name="transmit", type="string", length=255, nullable=false)
     */
    private $transmit;

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
     * Set token
     *
     * @param string $token
     * @return Wxdefault
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
     * Set reply_default
     *
     * @param string $replyDefault
     * @return Wxdefault
     */
    public function setReplyDefault($replyDefault)
    {
        $this->reply_default = $replyDefault;
    
        return $this;
    }

    /**
     * Get reply_default
     *
     * @return string 
     */
    public function getReplyDefault()
    {
        return $this->reply_default;
    }

    /**
     * Set subscribe_default
     *
     * @param string $subscribeDefault
     * @return Wxdefault
     */
    public function setSubscribeDefault($subscribeDefault)
    {
        $this->subscribe_default = $subscribeDefault;
    
        return $this;
    }

    /**
     * Get subscribe_default
     *
     * @return string 
     */
    public function getSubscribeDefault()
    {
        return $this->subscribe_default;
    }

    /**
     * Set unsubscribe_default
     *
     * @param string $unsubscribeDefault
     * @return Wxdefault
     */
    public function setUnsubscribeDefault($unsubscribeDefault)
    {
        $this->unsubscribe_default = $unsubscribeDefault;
    
        return $this;
    }

    /**
     * Get unsubscribe_default
     *
     * @return string 
     */
    public function getUnsubscribeDefault()
    {
        return $this->unsubscribe_default;
    }

    /**
     * Set transmit
     *
     * @param string $transmit
     * @return Wxdefault
     */
    public function setTransmit($transmit)
    {
        $this->transmit = $transmit;
    
        return $this;
    }

    /**
     * Get transmit
     *
     * @return string 
     */
    public function getTransmit()
    {
        return $this->transmit;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxdefault
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
     * @return Wxdefault
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
     * @return Wxdefault
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
     * @return Wxdefault
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
     * @return Wxdefault
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
     * @return Wxdefault
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
     * @return Wxdefault
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
     * @return Wxdefault
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
