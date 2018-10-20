<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Integral
 *
 * @ORM\Table(name="integral", indexes={@ORM\Index(name="ename", columns={"ename"})})
 * @ORM\Entity
 */
class Integral
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
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=100, nullable=false)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="initial", type="string", length=100, nullable=false)
     */
    private $initial;

    /**
     * @var integer
     *
     * @ORM\Column(name="sid", type="integer", length=10, nullable=false)
     */
    private $sid;

    /**
     * @var integer
     *
     * @ORM\Column(name="scurrency", type="integer", length=10, nullable=false)
     */
    private $scurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="eid", type="integer", length=10, nullable=false)
     */
    private $eid;

    /**
     * @var integer
     *
     * @ORM\Column(name="ecurrency", type="integer", length=10, nullable=false)
     */
    private $ecurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="ename", type="string", length=100, nullable=false)
     */
    private $ename;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", length=1, nullable=false)
     */
    private $checked = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="paying", type="smallint", length=2, nullable=false)
     */
    private $paying;

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
     * Set name
     *
     * @param string $name
     * @return Integral
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
     * Set unit
     *
     * @param string $unit
     * @return Integral
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set initial
     *
     * @param string $initial
     * @return Integral
     */
    public function setInitial($initial)
    {
        $this->initial = $initial;
    
        return $this;
    }

    /**
     * Get initial
     *
     * @return string 
     */
    public function getInitial()
    {
        return $this->initial;
    }

    /**
     * Set sid
     *
     * @param integer $sid
     * @return Integral
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    
        return $this;
    }

    /**
     * Get sid
     *
     * @return integer 
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set scurrency
     *
     * @param integer $scurrency
     * @return Integral
     */
    public function setScurrency($scurrency)
    {
        $this->scurrency = $scurrency;
    
        return $this;
    }

    /**
     * Get scurrency
     *
     * @return integer 
     */
    public function getScurrency()
    {
        return $this->scurrency;
    }

    /**
     * Set eid
     *
     * @param integer $eid
     * @return Integral
     */
    public function setEid($eid)
    {
        $this->eid = $eid;
    
        return $this;
    }

    /**
     * Get eid
     *
     * @return integer 
     */
    public function getEid()
    {
        return $this->eid;
    }

    /**
     * Set ecurrency
     *
     * @param integer $ecurrency
     * @return Integral
     */
    public function setEcurrency($ecurrency)
    {
        $this->ecurrency = $ecurrency;
    
        return $this;
    }

    /**
     * Get ecurrency
     *
     * @return integer 
     */
    public function getEcurrency()
    {
        return $this->ecurrency;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Integral
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ename
     *
     * @param string $ename
     * @return Integral
     */
    public function setEname($ename)
    {
        $this->ename = $ename;
    
        return $this;
    }

    /**
     * Get ename
     *
     * @return string 
     */
    public function getEname()
    {
        return $this->ename;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Integral
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
     * Set paying
     *
     * @param integer $paying
     * @return Integral
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
     * Set issystem
     *
     * @param boolean $issystem
     * @return Integral
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
     * @return Integral
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
     * @return Integral
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
     * @return Integral
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
     * @return Integral
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
     * @return Integral
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
     * @return Integral
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
