<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxkeyword
 *
 * @ORM\Table(name="wxkeyword")
 * @ORM\Entity
 */
class Wxkeyword
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
     * @ORM\Column(name="tips", type="string", length=120, nullable=false)
     */
    private $tips;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="text", length=255, nullable=false)
     */
    private $keyword;

    /**
     * @var string
     *
     * @ORM\Column(name="replytype", type="string", length=50, nullable=false)
     */
    private $replytype;

    /**
     * @var string
     *
     * @ORM\Column(name="replytext", type="text", length=255, nullable=false)
     */
    private $replytext;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=200, nullable=false)
     */
    private $token;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_switch", type="smallint", length=2, nullable=false)
     */
    private $is_switch;

    /**
     * @var string
     *
     * @ORM\Column(name="replyimage", type="string", length=200, nullable=false)
     */
    private $replyimage;

    /**
     * @var string
     *
     * @ORM\Column(name="replyvoice", type="string", length=200, nullable=false)
     */
    private $replyvoice;

    /**
     * @var string
     *
     * @ORM\Column(name="replyvideo", type="string", length=200, nullable=false)
     */
    private $replyvideo;

    /**
     * @var string
     *
     * @ORM\Column(name="replynews", type="string", length=200, nullable=false)
     */
    private $replynews;

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
     * Set tips
     *
     * @param string $tips
     * @return Wxkeyword
     */
    public function setTips($tips)
    {
        $this->tips = $tips;
    
        return $this;
    }

    /**
     * Get tips
     *
     * @return string 
     */
    public function getTips()
    {
        return $this->tips;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return Wxkeyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set replytype
     *
     * @param string $replytype
     * @return Wxkeyword
     */
    public function setReplytype($replytype)
    {
        $this->replytype = $replytype;
    
        return $this;
    }

    /**
     * Get replytype
     *
     * @return string 
     */
    public function getReplytype()
    {
        return $this->replytype;
    }

    /**
     * Set replytext
     *
     * @param string $replytext
     * @return Wxkeyword
     */
    public function setReplytext($replytext)
    {
        $this->replytext = $replytext;
    
        return $this;
    }

    /**
     * Get replytext
     *
     * @return string 
     */
    public function getReplytext()
    {
        return $this->replytext;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Wxkeyword
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
     * Set is_switch
     *
     * @param integer $isSwitch
     * @return Wxkeyword
     */
    public function setIsSwitch($isSwitch)
    {
        $this->is_switch = $isSwitch;
    
        return $this;
    }

    /**
     * Get is_switch
     *
     * @return integer 
     */
    public function getIsSwitch()
    {
        return $this->is_switch;
    }

    /**
     * Set replyimage
     *
     * @param string $replyimage
     * @return Wxkeyword
     */
    public function setReplyimage($replyimage)
    {
        $this->replyimage = $replyimage;
    
        return $this;
    }

    /**
     * Get replyimage
     *
     * @return string 
     */
    public function getReplyimage()
    {
        return $this->replyimage;
    }

    /**
     * Set replyvoice
     *
     * @param string $replyvoice
     * @return Wxkeyword
     */
    public function setReplyvoice($replyvoice)
    {
        $this->replyvoice = $replyvoice;
    
        return $this;
    }

    /**
     * Get replyvoice
     *
     * @return string 
     */
    public function getReplyvoice()
    {
        return $this->replyvoice;
    }

    /**
     * Set replyvideo
     *
     * @param string $replyvideo
     * @return Wxkeyword
     */
    public function setReplyvideo($replyvideo)
    {
        $this->replyvideo = $replyvideo;
    
        return $this;
    }

    /**
     * Get replyvideo
     *
     * @return string 
     */
    public function getReplyvideo()
    {
        return $this->replyvideo;
    }

    /**
     * Set replynews
     *
     * @param string $replynews
     * @return Wxkeyword
     */
    public function setReplynews($replynews)
    {
        $this->replynews = $replynews;
    
        return $this;
    }

    /**
     * Get replynews
     *
     * @return string 
     */
    public function getReplynews()
    {
        return $this->replynews;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxkeyword
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
     * @return Wxkeyword
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
     * @return Wxkeyword
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
     * @return Wxkeyword
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
     * @return Wxkeyword
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
     * @return Wxkeyword
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
     * @return Wxkeyword
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
     * @return Wxkeyword
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
