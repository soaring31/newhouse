<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demand
 *
 * @ORM\Table(name="demand")
 * @ORM\Entity
 */
class Demand
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
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="xingming", type="string", length=100, nullable=false)
     */
    private $xingming;

    /**
     * @var float
     *
     * @ORM\Column(name="mj", type="float", length=100, nullable=false)
     */
    private $mj;

    /**
     * @var float
     *
     * @ORM\Column(name="zj", type="float", length=100, nullable=false)
     */
    private $zj;

    /**
     * @var string
     *
     * @ORM\Column(name="jtyq", type="string", length=100, nullable=false)
     */
    private $jtyq;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", length=2, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models = 'demand';

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=0, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=10, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="enddate", type="integer", length=10, nullable=false)
     */
    private $enddate;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", length=100, nullable=false)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="valid", type="integer", length=10, nullable=false)
     */
    private $valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", length=10, nullable=false)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="shi", type="integer", length=10, nullable=false)
     */
    private $shi;

    /**
     * @var integer
     *
     * @ORM\Column(name="ting", type="integer", length=10, nullable=false)
     */
    private $ting;

    /**
     * @var string
     *
     * @ORM\Column(name="huxing", type="string", length=100, nullable=false)
     */
    private $huxing;

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
     * Set name
     *
     * @param string $name
     * @return Demand
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
     * Set tel
     *
     * @param string $tel
     * @return Demand
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    
        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set xingming
     *
     * @param string $xingming
     * @return Demand
     */
    public function setXingming($xingming)
    {
        $this->xingming = $xingming;
    
        return $this;
    }

    /**
     * Get xingming
     *
     * @return string 
     */
    public function getXingming()
    {
        return $this->xingming;
    }

    /**
     * Set mj
     *
     * @param float $mj
     * @return Demand
     */
    public function setMj($mj)
    {
        $this->mj = $mj;
    
        return $this;
    }

    /**
     * Get mj
     *
     * @return float 
     */
    public function getMj()
    {
        return $this->mj;
    }

    /**
     * Set zj
     *
     * @param float $zj
     * @return Demand
     */
    public function setZj($zj)
    {
        $this->zj = $zj;
    
        return $this;
    }

    /**
     * Get zj
     *
     * @return float 
     */
    public function getZj()
    {
        return $this->zj;
    }

    /**
     * Set jtyq
     *
     * @param string $jtyq
     * @return Demand
     */
    public function setJtyq($jtyq)
    {
        $this->jtyq = $jtyq;
    
        return $this;
    }

    /**
     * Get jtyq
     *
     * @return string 
     */
    public function getJtyq()
    {
        return $this->jtyq;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Demand
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
     * Set models
     *
     * @param string $models
     * @return Demand
     */
    public function setModels($models)
    {
        $this->models = $models;
    
        return $this;
    }

    /**
     * Get models
     *
     * @return string 
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Demand
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
     * Set keywords
     *
     * @param string $keywords
     * @return Demand
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Demand
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
     * Set area
     *
     * @param integer $area
     * @return Demand
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return integer 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set enddate
     *
     * @param integer $enddate
     * @return Demand
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    
        return $this;
    }

    /**
     * Get enddate
     *
     * @return integer 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set region
     *
     * @param integer $region
     * @return Demand
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return integer 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set valid
     *
     * @param integer $valid
     * @return Demand
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
     * Set category
     *
     * @param integer $category
     * @return Demand
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set shi
     *
     * @param integer $shi
     * @return Demand
     */
    public function setShi($shi)
    {
        $this->shi = $shi;
    
        return $this;
    }

    /**
     * Get shi
     *
     * @return integer 
     */
    public function getShi()
    {
        return $this->shi;
    }

    /**
     * Set ting
     *
     * @param integer $ting
     * @return Demand
     */
    public function setTing($ting)
    {
        $this->ting = $ting;
    
        return $this;
    }

    /**
     * Get ting
     *
     * @return integer 
     */
    public function getTing()
    {
        return $this->ting;
    }

    /**
     * Set huxing
     *
     * @param string $huxing
     * @return Demand
     */
    public function setHuxing($huxing)
    {
        $this->huxing = $huxing;
    
        return $this;
    }

    /**
     * Get huxing
     *
     * @return string 
     */
    public function getHuxing()
    {
        return $this->huxing;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Demand
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
     * @return Demand
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
     * @return Demand
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
     * @return Demand
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
     * @return Demand
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
     * @return Demand
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
     * @return Demand
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
     * @return Demand
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
