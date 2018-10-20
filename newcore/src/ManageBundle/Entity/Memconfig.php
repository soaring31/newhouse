<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Memconfig
 *
 * @ORM\Table(name="memconfig")
 * @ORM\Entity
 */
class Memconfig
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
     * @var integer
     *
     * @ORM\Column(name="enable", type="smallint", length=2, nullable=false)
     */
    private $enable;

    /**
     * @var float
     *
     * @ORM\Column(name="money", type="float", length=100, nullable=false)
     */
    private $money;

    /**
     * @var integer
     *
     * @ORM\Column(name="duedate", type="integer", length=10, nullable=false)
     */
    private $duedate;

    /**
     * @var integer
     *
     * @ORM\Column(name="tops", type="integer", length=10, nullable=false)
     */
    private $tops;

    /**
     * @var integer
     *
     * @ORM\Column(name="refreshs", type="integer", length=10, nullable=false)
     */
    private $refreshs;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", length=1, nullable=false)
     */
    private $issystem;

    /**
     * @var integer
     *
     * @ORM\Column(name="mem_group", type="integer", length=10, nullable=false)
     */
    private $mem_group;

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
     * @return Memconfig
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
     * Set enable
     *
     * @param integer $enable
     * @return Memconfig
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    
        return $this;
    }

    /**
     * Get enable
     *
     * @return integer 
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set money
     *
     * @param float $money
     * @return Memconfig
     */
    public function setMoney($money)
    {
        $this->money = $money;
    
        return $this;
    }

    /**
     * Get money
     *
     * @return float 
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Set duedate
     *
     * @param integer $duedate
     * @return Memconfig
     */
    public function setDuedate($duedate)
    {
        $this->duedate = $duedate;
    
        return $this;
    }

    /**
     * Get duedate
     *
     * @return integer 
     */
    public function getDuedate()
    {
        return $this->duedate;
    }

    /**
     * Set tops
     *
     * @param integer $tops
     * @return Memconfig
     */
    public function setTops($tops)
    {
        $this->tops = $tops;
    
        return $this;
    }

    /**
     * Get tops
     *
     * @return integer 
     */
    public function getTops()
    {
        return $this->tops;
    }

    /**
     * Set refreshs
     *
     * @param integer $refreshs
     * @return Memconfig
     */
    public function setRefreshs($refreshs)
    {
        $this->refreshs = $refreshs;
    
        return $this;
    }

    /**
     * Get refreshs
     *
     * @return integer 
     */
    public function getRefreshs()
    {
        return $this->refreshs;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return Memconfig
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
     * Set mem_group
     *
     * @param integer $memGroup
     * @return Memconfig
     */
    public function setMemGroup($memGroup)
    {
        $this->mem_group = $memGroup;
    
        return $this;
    }

    /**
     * Get mem_group
     *
     * @return integer 
     */
    public function getMemGroup()
    {
        return $this->mem_group;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Memconfig
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
     * @return Memconfig
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
     * @return Memconfig
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
     * @return Memconfig
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
     * @return Memconfig
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
     * @return Memconfig
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
     * @return Memconfig
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
