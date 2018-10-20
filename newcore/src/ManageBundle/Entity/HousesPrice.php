<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HousesPrice
 *
 * @ORM\Table(name="houses_price")
 * @ORM\Entity
 */
class HousesPrice
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
     * @ORM\Column(name="dj", type="string", length=100, nullable=false)
     */
    private $dj;

    /**
     * @var string
     *
     * @ORM\Column(name="jgjj", type="string", length=100, nullable=false)
     */
    private $jgjj;

    /**
     * @var string
     *
     * @ORM\Column(name="jdjj", type="string", length=100, nullable=false)
     */
    private $jdjj;

    /**
     * @var string
     *
     * @ORM\Column(name="bdsm", type="string", length=100, nullable=false)
     */
    private $bdsm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="jtime", type="date", length=10, nullable=false)
     */
    private $jtime;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="aid", type="string", length=100, nullable=false)
     */
    private $aid;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", length=10, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="trend", type="string", length=100, nullable=false)
     */
    private $trend;

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
     * Set dj
     *
     * @param string $dj
     * @return HousesPrice
     */
    public function setDj($dj)
    {
        $this->dj = $dj;
    
        return $this;
    }

    /**
     * Get dj
     *
     * @return string 
     */
    public function getDj()
    {
        return $this->dj;
    }

    /**
     * Set jgjj
     *
     * @param string $jgjj
     * @return HousesPrice
     */
    public function setJgjj($jgjj)
    {
        $this->jgjj = $jgjj;
    
        return $this;
    }

    /**
     * Get jgjj
     *
     * @return string 
     */
    public function getJgjj()
    {
        return $this->jgjj;
    }

    /**
     * Set jdjj
     *
     * @param string $jdjj
     * @return HousesPrice
     */
    public function setJdjj($jdjj)
    {
        $this->jdjj = $jdjj;
    
        return $this;
    }

    /**
     * Get jdjj
     *
     * @return string 
     */
    public function getJdjj()
    {
        return $this->jdjj;
    }

    /**
     * Set bdsm
     *
     * @param string $bdsm
     * @return HousesPrice
     */
    public function setBdsm($bdsm)
    {
        $this->bdsm = $bdsm;
    
        return $this;
    }

    /**
     * Get bdsm
     *
     * @return string 
     */
    public function getBdsm()
    {
        return $this->bdsm;
    }

    /**
     * Set jtime
     *
     * @param \DateTime $jtime
     * @return HousesPrice
     */
    public function setJtime($jtime)
    {
        $this->jtime = $jtime;
    
        return $this;
    }

    /**
     * Get jtime
     *
     * @return \DateTime 
     */
    public function getJtime()
    {
        return $this->jtime;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return HousesPrice
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
     * @param string $aid
     * @return HousesPrice
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    
        return $this;
    }

    /**
     * Get aid
     *
     * @return string 
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return HousesPrice
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
     * Set trend
     *
     * @param string $trend
     * @return HousesPrice
     */
    public function setTrend($trend)
    {
        $this->trend = $trend;
    
        return $this;
    }

    /**
     * Get trend
     *
     * @return string 
     */
    public function getTrend()
    {
        return $this->trend;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return HousesPrice
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
     * @return HousesPrice
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
     * @return HousesPrice
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
     * @return HousesPrice
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
     * @return HousesPrice
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
     * @return HousesPrice
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
     * @return HousesPrice
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
     * @return HousesPrice
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
