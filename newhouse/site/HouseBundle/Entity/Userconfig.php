<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userconfig
 *
 * @ORM\Table(name="userconfig")
 * @ORM\Entity
 */
class Userconfig
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
     * @ORM\Column(name="rentalperiod", type="integer", length=10, nullable=false)
     */
    private $rentalperiod;

    /**
     * @var integer
     *
     * @ORM\Column(name="rentdrenum", type="integer", length=10, nullable=false)
     */
    private $rentdrenum;

    /**
     * @var integer
     *
     * @ORM\Column(name="drefresh", type="integer", length=10, nullable=false)
     */
    private $drefresh;

    /**
     * @var integer
     *
     * @ORM\Column(name="demandrenum", type="integer", length=10, nullable=false)
     */
    private $demandrenum;

    /**
     * @var integer
     *
     * @ORM\Column(name="demandqx", type="integer", length=10, nullable=false)
     */
    private $demandqx;

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
     * @return Userconfig
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
     * Set rentalperiod
     *
     * @param integer $rentalperiod
     * @return Userconfig
     */
    public function setRentalperiod($rentalperiod)
    {
        $this->rentalperiod = $rentalperiod;
    
        return $this;
    }

    /**
     * Get rentalperiod
     *
     * @return integer 
     */
    public function getRentalperiod()
    {
        return $this->rentalperiod;
    }

    /**
     * Set rentdrenum
     *
     * @param integer $rentdrenum
     * @return Userconfig
     */
    public function setRentdrenum($rentdrenum)
    {
        $this->rentdrenum = $rentdrenum;
    
        return $this;
    }

    /**
     * Get rentdrenum
     *
     * @return integer 
     */
    public function getRentdrenum()
    {
        return $this->rentdrenum;
    }

    /**
     * Set drefresh
     *
     * @param integer $drefresh
     * @return Userconfig
     */
    public function setDrefresh($drefresh)
    {
        $this->drefresh = $drefresh;
    
        return $this;
    }

    /**
     * Get drefresh
     *
     * @return integer 
     */
    public function getDrefresh()
    {
        return $this->drefresh;
    }

    /**
     * Set demandrenum
     *
     * @param integer $demandrenum
     * @return Userconfig
     */
    public function setDemandrenum($demandrenum)
    {
        $this->demandrenum = $demandrenum;
    
        return $this;
    }

    /**
     * Get demandrenum
     *
     * @return integer 
     */
    public function getDemandrenum()
    {
        return $this->demandrenum;
    }

    /**
     * Set demandqx
     *
     * @param integer $demandqx
     * @return Userconfig
     */
    public function setDemandqx($demandqx)
    {
        $this->demandqx = $demandqx;
    
        return $this;
    }

    /**
     * Get demandqx
     *
     * @return integer 
     */
    public function getDemandqx()
    {
        return $this->demandqx;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Userconfig
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
     * @return Userconfig
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
     * Set checked
     *
     * @param boolean $checked
     * @return Userconfig
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
     * @return Userconfig
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
     * @return Userconfig
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
     * @return Userconfig
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
     * @return Userconfig
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
     * @return Userconfig
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
     * @return Userconfig
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
