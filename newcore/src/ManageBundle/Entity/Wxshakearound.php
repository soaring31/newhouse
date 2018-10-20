<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxshakearound
 *
 * @ORM\Table(name="wxshakearound")
 * @ORM\Entity
 */
class Wxshakearound
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
     * @ORM\Column(name="deviceid", type="string", length=50, nullable=false)
     */
    private $deviceid;

    /**
     * @var string
     *
     * @ORM\Column(name="devicetitle", type="string", length=50, nullable=false)
     */
    private $devicetitle;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=100, nullable=false)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="major", type="string", length=50, nullable=false)
     */
    private $major;

    /**
     * @var string
     *
     * @ORM\Column(name="minor", type="string", length=50, nullable=false)
     */
    private $minor;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", length=2, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_url", type="string", length=100, nullable=false)
     */
    private $icon_url;

    /**
     * @var string
     *
     * @ORM\Column(name="page_url", type="string", length=100, nullable=false)
     */
    private $page_url;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=1000, nullable=false)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="authtest", type="integer", length=10, nullable=false)
     */
    private $authtest;

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
     * Set deviceid
     *
     * @param string $deviceid
     * @return Wxshakearound
     */
    public function setDeviceid($deviceid)
    {
        $this->deviceid = $deviceid;
    
        return $this;
    }

    /**
     * Get deviceid
     *
     * @return string 
     */
    public function getDeviceid()
    {
        return $this->deviceid;
    }

    /**
     * Set devicetitle
     *
     * @param string $devicetitle
     * @return Wxshakearound
     */
    public function setDevicetitle($devicetitle)
    {
        $this->devicetitle = $devicetitle;
    
        return $this;
    }

    /**
     * Get devicetitle
     *
     * @return string 
     */
    public function getDevicetitle()
    {
        return $this->devicetitle;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     * @return Wxshakearound
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    
        return $this;
    }

    /**
     * Get uuid
     *
     * @return string 
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set major
     *
     * @param string $major
     * @return Wxshakearound
     */
    public function setMajor($major)
    {
        $this->major = $major;
    
        return $this;
    }

    /**
     * Get major
     *
     * @return string 
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set minor
     *
     * @param string $minor
     * @return Wxshakearound
     */
    public function setMinor($minor)
    {
        $this->minor = $minor;
    
        return $this;
    }

    /**
     * Get minor
     *
     * @return string 
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Wxshakearound
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
     * Set title
     *
     * @param string $title
     * @return Wxshakearound
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Wxshakearound
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set icon_url
     *
     * @param string $iconUrl
     * @return Wxshakearound
     */
    public function setIconUrl($iconUrl)
    {
        $this->icon_url = $iconUrl;
    
        return $this;
    }

    /**
     * Get icon_url
     *
     * @return string 
     */
    public function getIconUrl()
    {
        return $this->icon_url;
    }

    /**
     * Set page_url
     *
     * @param string $pageUrl
     * @return Wxshakearound
     */
    public function setPageUrl($pageUrl)
    {
        $this->page_url = $pageUrl;
    
        return $this;
    }

    /**
     * Get page_url
     *
     * @return string 
     */
    public function getPageUrl()
    {
        return $this->page_url;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Wxshakearound
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set authtest
     *
     * @param integer $authtest
     * @return Wxshakearound
     */
    public function setAuthtest($authtest)
    {
        $this->authtest = $authtest;
    
        return $this;
    }

    /**
     * Get authtest
     *
     * @return integer 
     */
    public function getAuthtest()
    {
        return $this->authtest;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxshakearound
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
     * @return Wxshakearound
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
     * @return Wxshakearound
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
     * @return Wxshakearound
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
     * @return Wxshakearound
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
     * @return Wxshakearound
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
     * @return Wxshakearound
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
     * @return Wxshakearound
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
