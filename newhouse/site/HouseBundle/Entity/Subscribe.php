<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscribe
 *
 * @ORM\Table(name="subscribe", indexes={@ORM\Index(name="city", columns={"city"})})
 * @ORM\Entity
 */
class Subscribe
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
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=11, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="city", type="integer", length=11, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="filter_search", type="text", length=0, nullable=false)
     */
    private $filter_search;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="time", length=10, nullable=false)
     */
    private $create_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_delete", type="smallint", length=1, nullable=false)
     */
    private $is_delete;

    /**
     * @var integer
     *
     * @ORM\Column(name="house_id", type="integer", length=11, nullable=false)
     */
    private $house_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="tel", type="integer", length=11, nullable=false)
     */
    private $tel;

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
     * @ORM\Column(name="update_time", type="integer", length=10, nullable=false)
     */
    private $update_time;


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
     * Set uid
     *
     * @param integer $uid
     * @return Subscribe
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
     * Set city
     *
     * @param integer $city
     * @return Subscribe
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return integer 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set filter_search
     *
     * @param string $filterSearch
     * @return Subscribe
     */
    public function setFilterSearch($filterSearch)
    {
        $this->filter_search = $filterSearch;
    
        return $this;
    }

    /**
     * Get filter_search
     *
     * @return string 
     */
    public function getFilterSearch()
    {
        return $this->filter_search;
    }

    /**
     * Set create_time
     *
     * @param \DateTime $createTime
     * @return Subscribe
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;
    
        return $this;
    }

    /**
     * Get create_time
     *
     * @return \DateTime 
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set is_delete
     *
     * @param integer $isDelete
     * @return Subscribe
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;
    
        return $this;
    }

    /**
     * Get is_delete
     *
     * @return integer 
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }

    /**
     * Set house_id
     *
     * @param integer $houseId
     * @return Subscribe
     */
    public function setHouseId($houseId)
    {
        $this->house_id = $houseId;
    
        return $this;
    }

    /**
     * Get house_id
     *
     * @return integer 
     */
    public function getHouseId()
    {
        return $this->house_id;
    }

    /**
     * Set tel
     *
     * @param integer $tel
     * @return Subscribe
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    
        return $this;
    }

    /**
     * Get tel
     *
     * @return integer 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Subscribe
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
     * @return Subscribe
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
     * @return Subscribe
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
     * @return Subscribe
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
     * @return Subscribe
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
     * Set update_time
     *
     * @param integer $updateTime
     * @return Subscribe
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
}
