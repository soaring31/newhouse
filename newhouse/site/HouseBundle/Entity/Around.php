<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Around
 *
 * @ORM\Table(name="around")
 * @ORM\Entity
 */
class Around
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
     * @ORM\Column(name="abstract", type="text", length=0, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=100, nullable=false)
     */
    private $map;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var integer
     *
     * @ORM\Column(name="jibie", type="integer", length=2, nullable=false)
     */
    private $jibie;

    /**
     * @var integer
     *
     * @ORM\Column(name="leibie", type="integer", length=2, nullable=false)
     */
    private $leibie;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="houses", type="integer", length=10, nullable=false)
     */
    private $houses;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", length=10, nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=100, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="school_type", type="integer", length=2, nullable=false)
     */
    private $school_type;

    /**
     * @var string
     *
     * @ORM\Column(name="geohash", type="string", length=20, nullable=false)
     */
    private $geohash;

    /**
     * @var integer
     *
     * @ORM\Column(name="srcid_", type="integer", length=11, nullable=false)
     */
    private $srcid_;

    /**
     * @var string
     *
     * @ORM\Column(name="polyline", type="text", length=0, nullable=false)
     */
    private $polyline;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", length=100, nullable=false)
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", length=100, nullable=false)
     */
    private $lat;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=100, nullable=false)
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", length=100, nullable=false)
     */
    private $region;

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
     * @return Around
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
     * Set abstract
     *
     * @param string $abstract
     * @return Around
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    
        return $this;
    }

    /**
     * Get abstract
     *
     * @return string 
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return Around
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    
        return $this;
    }

    /**
     * Get thumb
     *
     * @return string 
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Around
     */
    public function setMap($map)
    {
        $this->map = $map;
    
        return $this;
    }

    /**
     * Get map
     *
     * @return string 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Around
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
     * Set jibie
     *
     * @param integer $jibie
     * @return Around
     */
    public function setJibie($jibie)
    {
        $this->jibie = $jibie;
    
        return $this;
    }

    /**
     * Get jibie
     *
     * @return integer 
     */
    public function getJibie()
    {
        return $this->jibie;
    }

    /**
     * Set leibie
     *
     * @param integer $leibie
     * @return Around
     */
    public function setLeibie($leibie)
    {
        $this->leibie = $leibie;
    
        return $this;
    }

    /**
     * Get leibie
     *
     * @return integer 
     */
    public function getLeibie()
    {
        return $this->leibie;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Around
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Around
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    
        return $this;
    }

    /**
     * Get checked
     *
     * @return integer 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set houses
     *
     * @param integer $houses
     * @return Around
     */
    public function setHouses($houses)
    {
        $this->houses = $houses;
    
        return $this;
    }

    /**
     * Get houses
     *
     * @return integer 
     */
    public function getHouses()
    {
        return $this->houses;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Around
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
     * Set area
     *
     * @param string $area
     * @return Around
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set school_type
     *
     * @param integer $schoolType
     * @return Around
     */
    public function setSchoolType($schoolType)
    {
        $this->school_type = $schoolType;
    
        return $this;
    }

    /**
     * Get school_type
     *
     * @return integer 
     */
    public function getSchoolType()
    {
        return $this->school_type;
    }

    /**
     * Set geohash
     *
     * @param string $geohash
     * @return Around
     */
    public function setGeohash($geohash)
    {
        $this->geohash = $geohash;
    
        return $this;
    }

    /**
     * Get geohash
     *
     * @return string 
     */
    public function getGeohash()
    {
        return $this->geohash;
    }

    /**
     * Set srcid_
     *
     * @param integer $srcid
     * @return Around
     */
    public function setSrcid($srcid)
    {
        $this->srcid_ = $srcid;
    
        return $this;
    }

    /**
     * Get srcid_
     *
     * @return integer 
     */
    public function getSrcid()
    {
        return $this->srcid_;
    }

    /**
     * Set polyline
     *
     * @param string $polyline
     * @return Around
     */
    public function setPolyline($polyline)
    {
        $this->polyline = $polyline;
    
        return $this;
    }

    /**
     * Get polyline
     *
     * @return string 
     */
    public function getPolyline()
    {
        return $this->polyline;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Around
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Around
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Around
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
    
        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer 
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set region
     *
     * @param integer $region
     * @return Around
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
     * Set attributes
     *
     * @param string $attributes
     * @return Around
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
     * @return Around
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
     * @return Around
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
     * @return Around
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
     * @return Around
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
     * @return Around
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
     * @return Around
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
