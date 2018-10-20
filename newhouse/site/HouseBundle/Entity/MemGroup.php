<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemGroup
 *
 * @ORM\Table(name="mem_group", indexes={@ORM\Index(name="aid", columns={"aid"})})
 * @ORM\Entity
 */
class MemGroup
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
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", length=1, nullable=false)
     */
    private $checked = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="valid", type="integer", length=10, nullable=false)
     */
    private $valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="overdue", type="smallint", length=2, nullable=false)
     */
    private $overdue;

    /**
     * @var string
     *
     * @ORM\Column(name="ico", type="text", length=100, nullable=false)
     */
    private $ico;

    /**
     * @var integer
     *
     * @ORM\Column(name="form_id", type="integer", length=10, nullable=false)
     */
    private $form_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="autoinit", type="smallint", length=2, nullable=false)
     */
    private $autoinit;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=10, nullable=false)
     */
    private $aid;

    /**
     * @var integer
     *
     * @ORM\Column(name="mincurrency", type="integer", length=10, nullable=false)
     */
    private $mincurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxcurrency", type="integer", length=10, nullable=false)
     */
    private $maxcurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=100, nullable=false)
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", length=1, nullable=false)
     */
    private $issystem;

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
     * Set checked
     *
     * @param boolean $checked
     * @return MemGroup
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
     * Set valid
     *
     * @param integer $valid
     * @return MemGroup
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    
        return $this;
    }

    /**
     * Get valid
     *
     * @return integer 
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set overdue
     *
     * @param integer $overdue
     * @return MemGroup
     */
    public function setOverdue($overdue)
    {
        $this->overdue = $overdue;
    
        return $this;
    }

    /**
     * Get overdue
     *
     * @return integer 
     */
    public function getOverdue()
    {
        return $this->overdue;
    }

    /**
     * Set ico
     *
     * @param string $ico
     * @return MemGroup
     */
    public function setIco($ico)
    {
        $this->ico = $ico;
    
        return $this;
    }

    /**
     * Get ico
     *
     * @return string 
     */
    public function getIco()
    {
        return $this->ico;
    }

    /**
     * Set form_id
     *
     * @param integer $formId
     * @return MemGroup
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
     * Set autoinit
     *
     * @param integer $autoinit
     * @return MemGroup
     */
    public function setAutoinit($autoinit)
    {
        $this->autoinit = $autoinit;
    
        return $this;
    }

    /**
     * Get autoinit
     *
     * @return integer 
     */
    public function getAutoinit()
    {
        return $this->autoinit;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MemGroup
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
     * Set aid
     *
     * @param integer $aid
     * @return MemGroup
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
     * Set mincurrency
     *
     * @param integer $mincurrency
     * @return MemGroup
     */
    public function setMincurrency($mincurrency)
    {
        $this->mincurrency = $mincurrency;
    
        return $this;
    }

    /**
     * Get mincurrency
     *
     * @return integer 
     */
    public function getMincurrency()
    {
        return $this->mincurrency;
    }

    /**
     * Set maxcurrency
     *
     * @param integer $maxcurrency
     * @return MemGroup
     */
    public function setMaxcurrency($maxcurrency)
    {
        $this->maxcurrency = $maxcurrency;
    
        return $this;
    }

    /**
     * Get maxcurrency
     *
     * @return integer 
     */
    public function getMaxcurrency()
    {
        return $this->maxcurrency;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return MemGroup
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return MemGroup
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
     * Set attributes
     *
     * @param string $attributes
     * @return MemGroup
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
     * @return MemGroup
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
     * @return MemGroup
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
     * @return MemGroup
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
     * @return MemGroup
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
     * @return MemGroup
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
