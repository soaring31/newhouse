<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemTypes
 *
 * @ORM\Table(name="mem_types", indexes={@ORM\Index(name="mode", columns={"mode"}), @ORM\Index(name="ischeck", columns={"ischeck"})})
 * @ORM\Entity
 */
class MemTypes
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", length=1, nullable=false)
     */
    private $checked = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="form_id", type="integer", length=10, nullable=false)
     */
    private $form_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="mode", type="smallint", length=3, nullable=false)
     */
    private $mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="integral", type="integer", length=10, nullable=false)
     */
    private $integral;

    /**
     * @var integer
     *
     * @ORM\Column(name="allow", type="integer", length=10, nullable=false)
     */
    private $allow;

    /**
     * @var string
     *
     * @ORM\Column(name="attach", type="string", length=10, nullable=false)
     */
    private $attach;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", length=1, nullable=false)
     */
    private $issystem;

    /**
     * @var integer
     *
     * @ORM\Column(name="ischeck", type="smallint", length=2, nullable=false)
     */
    private $ischeck;

    /**
     * @var string
     *
     * @ORM\Column(name="usertplid", type="string", length=100, nullable=false)
     */
    private $usertplid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="autodown", type="boolean", length=1, nullable=false)
     */
    private $autodown;

    /**
     * @var string
     *
     * @ORM\Column(name="urlparams", type="string", length=100, nullable=false)
     */
    private $urlparams;

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
     * Set name
     *
     * @param string $name
     * @return MemTypes
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
     * Set checked
     *
     * @param boolean $checked
     * @return MemTypes
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
     * Set form_id
     *
     * @param integer $formId
     * @return MemTypes
     */
    public function setFormId($formId)
    {
        $this->form_id = $formId;
    
        return $this;
    }

    /**
     * Get form_id
     *
     * @return integer 
     */
    public function getFormId()
    {
        return $this->form_id;
    }

    /**
     * Set mode
     *
     * @param integer $mode
     * @return MemTypes
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    
        return $this;
    }

    /**
     * Get mode
     *
     * @return integer 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set integral
     *
     * @param integer $integral
     * @return MemTypes
     */
    public function setIntegral($integral)
    {
        $this->integral = $integral;
    
        return $this;
    }

    /**
     * Get integral
     *
     * @return integer 
     */
    public function getIntegral()
    {
        return $this->integral;
    }

    /**
     * Set allow
     *
     * @param integer $allow
     * @return MemTypes
     */
    public function setAllow($allow)
    {
        $this->allow = $allow;
    
        return $this;
    }

    /**
     * Get allow
     *
     * @return integer 
     */
    public function getAllow()
    {
        return $this->allow;
    }

    /**
     * Set attach
     *
     * @param string $attach
     * @return MemTypes
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;
    
        return $this;
    }

    /**
     * Get attach
     *
     * @return string 
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return MemTypes
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
     * Set ischeck
     *
     * @param integer $ischeck
     * @return MemTypes
     */
    public function setIscheck($ischeck)
    {
        $this->ischeck = $ischeck;
    
        return $this;
    }

    /**
     * Get ischeck
     *
     * @return integer 
     */
    public function getIscheck()
    {
        return $this->ischeck;
    }

    /**
     * Set usertplid
     *
     * @param string $usertplid
     * @return MemTypes
     */
    public function setUsertplid($usertplid)
    {
        $this->usertplid = $usertplid;
    
        return $this;
    }

    /**
     * Get usertplid
     *
     * @return string 
     */
    public function getUsertplid()
    {
        return $this->usertplid;
    }

    /**
     * Set autodown
     *
     * @param boolean $autodown
     * @return MemTypes
     */
    public function setAutodown($autodown)
    {
        $this->autodown = $autodown;
    
        return $this;
    }

    /**
     * Get autodown
     *
     * @return boolean 
     */
    public function getAutodown()
    {
        return $this->autodown;
    }

    /**
     * Set urlparams
     *
     * @param string $urlparams
     * @return MemTypes
     */
    public function setUrlparams($urlparams)
    {
        $this->urlparams = $urlparams;
    
        return $this;
    }

    /**
     * Get urlparams
     *
     * @return string 
     */
    public function getUrlparams()
    {
        return $this->urlparams;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return MemTypes
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
     * @return MemTypes
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
     * Set identifier
     *
     * @param string $identifier
     * @return MemTypes
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
     * @return MemTypes
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
     * @return MemTypes
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
     * @return MemTypes
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
