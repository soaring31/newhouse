<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxpoi
 *
 * @ORM\Table(name="wxpoi", indexes={@ORM\Index(name="business_name", columns={"business_name"})})
 * @ORM\Entity
 */
class Wxpoi
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
     * @ORM\Column(name="sid", type="string", length=50, nullable=false)
     */
    private $sid;

    /**
     * @var string
     *
     * @ORM\Column(name="business_name", type="string", length=30, nullable=false)
     */
    private $business_name;

    /**
     * @var string
     *
     * @ORM\Column(name="branch_name", type="string", length=100, nullable=false)
     */
    private $branch_name;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=20, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=20, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=20, nullable=false)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=false)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="categories", type="string", length=100, nullable=false)
     */
    private $categories;

    /**
     * @var integer
     *
     * @ORM\Column(name="offset_type", type="integer", length=10, nullable=false)
     */
    private $offset_type = '1';

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", length=100, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=100, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_list", type="text", length=0, nullable=false)
     */
    private $photo_list;

    /**
     * @var string
     *
     * @ORM\Column(name="recommend", type="string", length=100, nullable=false)
     */
    private $recommend;

    /**
     * @var string
     *
     * @ORM\Column(name="special", type="string", length=100, nullable=false)
     */
    private $special;

    /**
     * @var string
     *
     * @ORM\Column(name="introduction", type="string", length=100, nullable=false)
     */
    private $introduction;

    /**
     * @var string
     *
     * @ORM\Column(name="open_time", type="string", length=100, nullable=false)
     */
    private $open_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg_price", type="integer", length=10, nullable=false)
     */
    private $avg_price;

    /**
     * @var integer
     *
     * @ORM\Column(name="authtest", type="integer", length=10, nullable=false)
     */
    private $authtest;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=50, nullable=false)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sync", type="boolean", length=1, nullable=false)
     */
    private $is_sync;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_base", type="boolean", length=1, nullable=false)
     */
    private $is_base;

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
     * Set sid
     *
     * @param string $sid
     * @return Wxpoi
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    
        return $this;
    }

    /**
     * Get sid
     *
     * @return string 
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set business_name
     *
     * @param string $businessName
     * @return Wxpoi
     */
    public function setBusinessName($businessName)
    {
        $this->business_name = $businessName;
    
        return $this;
    }

    /**
     * Get business_name
     *
     * @return string 
     */
    public function getBusinessName()
    {
        return $this->business_name;
    }

    /**
     * Set branch_name
     *
     * @param string $branchName
     * @return Wxpoi
     */
    public function setBranchName($branchName)
    {
        $this->branch_name = $branchName;
    
        return $this;
    }

    /**
     * Get branch_name
     *
     * @return string 
     */
    public function getBranchName()
    {
        return $this->branch_name;
    }

    /**
     * Set province
     *
     * @param string $province
     * @return Wxpoi
     */
    public function setProvince($province)
    {
        $this->province = $province;
    
        return $this;
    }

    /**
     * Get province
     *
     * @return string 
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Wxpoi
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set district
     *
     * @param string $district
     * @return Wxpoi
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    
        return $this;
    }

    /**
     * Get district
     *
     * @return string 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Wxpoi
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
     * Set telephone
     *
     * @param string $telephone
     * @return Wxpoi
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set categories
     *
     * @param string $categories
     * @return Wxpoi
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    
        return $this;
    }

    /**
     * Get categories
     *
     * @return string 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set offset_type
     *
     * @param integer $offsetType
     * @return Wxpoi
     */
    public function setOffsetType($offsetType)
    {
        $this->offset_type = $offsetType;
    
        return $this;
    }

    /**
     * Get offset_type
     *
     * @return integer 
     */
    public function getOffsetType()
    {
        return $this->offset_type;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Wxpoi
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Wxpoi
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set photo_list
     *
     * @param string $photoList
     * @return Wxpoi
     */
    public function setPhotoList($photoList)
    {
        $this->photo_list = $photoList;
    
        return $this;
    }

    /**
     * Get photo_list
     *
     * @return string 
     */
    public function getPhotoList()
    {
        return $this->photo_list;
    }

    /**
     * Set recommend
     *
     * @param string $recommend
     * @return Wxpoi
     */
    public function setRecommend($recommend)
    {
        $this->recommend = $recommend;
    
        return $this;
    }

    /**
     * Get recommend
     *
     * @return string 
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * Set special
     *
     * @param string $special
     * @return Wxpoi
     */
    public function setSpecial($special)
    {
        $this->special = $special;
    
        return $this;
    }

    /**
     * Get special
     *
     * @return string 
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     * @return Wxpoi
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
    
        return $this;
    }

    /**
     * Get introduction
     *
     * @return string 
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set open_time
     *
     * @param string $openTime
     * @return Wxpoi
     */
    public function setOpenTime($openTime)
    {
        $this->open_time = $openTime;
    
        return $this;
    }

    /**
     * Get open_time
     *
     * @return string 
     */
    public function getOpenTime()
    {
        return $this->open_time;
    }

    /**
     * Set avg_price
     *
     * @param integer $avgPrice
     * @return Wxpoi
     */
    public function setAvgPrice($avgPrice)
    {
        $this->avg_price = $avgPrice;
    
        return $this;
    }

    /**
     * Get avg_price
     *
     * @return integer 
     */
    public function getAvgPrice()
    {
        return $this->avg_price;
    }

    /**
     * Set authtest
     *
     * @param integer $authtest
     * @return Wxpoi
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
     * Set token
     *
     * @param string $token
     * @return Wxpoi
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set is_sync
     *
     * @param boolean $isSync
     * @return Wxpoi
     */
    public function setIsSync($isSync)
    {
        $this->is_sync = $isSync;
    
        return $this;
    }

    /**
     * Get is_sync
     *
     * @return boolean 
     */
    public function getIsSync()
    {
        return $this->is_sync;
    }

    /**
     * Set is_base
     *
     * @param boolean $isBase
     * @return Wxpoi
     */
    public function setIsBase($isBase)
    {
        $this->is_base = $isBase;
    
        return $this;
    }

    /**
     * Get is_base
     *
     * @return boolean 
     */
    public function getIsBase()
    {
        return $this->is_base;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxpoi
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
     * @return Wxpoi
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
     * @return Wxpoi
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
     * @return Wxpoi
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
     * @return Wxpoi
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
     * @return Wxpoi
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
     * @return Wxpoi
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
     * @return Wxpoi
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
