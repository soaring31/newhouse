<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Build
 *
 * @ORM\Table(name="build", indexes={@ORM\Index(name="cate_status", columns={"cate_status"}), @ORM\Index(name="aid", columns={"aid"})})
 * @ORM\Entity
 */
class Build
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
     * @ORM\Column(name="zxcd", type="smallint", length=2, nullable=false)
     */
    private $zxcd;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_status", type="smallint", length=2, nullable=false)
     */
    private $cate_status;

    /**
     * @var string
     *
     * @ORM\Column(name="dongtai", type="string", length=100, nullable=false)
     */
    private $dongtai;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit", type="integer", length=100, nullable=false)
     */
    private $unit;

    /**
     * @var integer
     *
     * @ORM\Column(name="floor", type="integer", length=100, nullable=false)
     */
    private $floor;

    /**
     * @var integer
     *
     * @ORM\Column(name="hushu", type="integer", length=100, nullable=false)
     */
    private $hushu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="kpsj", type="date", length=10, nullable=false)
     */
    private $kpsj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="jfsj", type="date", length=10, nullable=false)
     */
    private $jfsj;

    /**
     * @var string
     *
     * @ORM\Column(name="sgjd", type="string", length=100, nullable=false)
     */
    private $sgjd;

    /**
     * @var string
     *
     * @ORM\Column(name="xkzh", type="string", length=100, nullable=false)
     */
    private $xkzh;

    /**
     * @var string
     *
     * @ORM\Column(name="shapan", type="string", length=100, nullable=false)
     */
    private $shapan;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=100, nullable=false)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=100, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="seat", type="string", length=100, nullable=false)
     */
    private $seat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="xkzh_time", type="date", length=10, nullable=false)
     */
    private $xkzh_time;

    /**
     * @var string
     *
     * @ORM\Column(name="build_year", type="string", length=50, nullable=false)
     */
    private $build_year;

    /**
     * @var integer
     *
     * @ORM\Column(name="house_num", type="integer", length=11, nullable=false)
     */
    private $house_num;

    /**
     * @var string
     *
     * @ORM\Column(name="vacancy_floor", type="string", length=100, nullable=false)
     */
    private $vacancy_floor;

    /**
     * @var integer
     *
     * @ORM\Column(name="elevator", type="integer", length=11, nullable=false)
     */
    private $elevator;

    /**
     * @var string
     *
     * @ORM\Column(name="door_id", type="string", length=50, nullable=false)
     */
    private $door_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="build_type", type="smallint", length=2, nullable=false)
     */
    private $build_type;

    /**
     * @var string
     *
     * @ORM\Column(name="ladder", type="string", length=100, nullable=false)
     */
    private $ladder;

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
     * @return Build
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
     * Set zxcd
     *
     * @param integer $zxcd
     * @return Build
     */
    public function setZxcd($zxcd)
    {
        $this->zxcd = $zxcd;
    
        return $this;
    }

    /**
     * Get zxcd
     *
     * @return integer 
     */
    public function getZxcd()
    {
        return $this->zxcd;
    }

    /**
     * Set cate_status
     *
     * @param integer $cateStatus
     * @return Build
     */
    public function setCateStatus($cateStatus)
    {
        $this->cate_status = $cateStatus;
    
        return $this;
    }

    /**
     * Get cate_status
     *
     * @return integer 
     */
    public function getCateStatus()
    {
        return $this->cate_status;
    }

    /**
     * Set dongtai
     *
     * @param string $dongtai
     * @return Build
     */
    public function setDongtai($dongtai)
    {
        $this->dongtai = $dongtai;
    
        return $this;
    }

    /**
     * Get dongtai
     *
     * @return string 
     */
    public function getDongtai()
    {
        return $this->dongtai;
    }

    /**
     * Set unit
     *
     * @param integer $unit
     * @return Build
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return integer 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set floor
     *
     * @param integer $floor
     * @return Build
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    
        return $this;
    }

    /**
     * Get floor
     *
     * @return integer 
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set hushu
     *
     * @param integer $hushu
     * @return Build
     */
    public function setHushu($hushu)
    {
        $this->hushu = $hushu;
    
        return $this;
    }

    /**
     * Get hushu
     *
     * @return integer 
     */
    public function getHushu()
    {
        return $this->hushu;
    }

    /**
     * Set kpsj
     *
     * @param \DateTime $kpsj
     * @return Build
     */
    public function setKpsj($kpsj)
    {
        $this->kpsj = $kpsj;
    
        return $this;
    }

    /**
     * Get kpsj
     *
     * @return \DateTime 
     */
    public function getKpsj()
    {
        return $this->kpsj;
    }

    /**
     * Set jfsj
     *
     * @param \DateTime $jfsj
     * @return Build
     */
    public function setJfsj($jfsj)
    {
        $this->jfsj = $jfsj;
    
        return $this;
    }

    /**
     * Get jfsj
     *
     * @return \DateTime 
     */
    public function getJfsj()
    {
        return $this->jfsj;
    }

    /**
     * Set sgjd
     *
     * @param string $sgjd
     * @return Build
     */
    public function setSgjd($sgjd)
    {
        $this->sgjd = $sgjd;
    
        return $this;
    }

    /**
     * Get sgjd
     *
     * @return string 
     */
    public function getSgjd()
    {
        return $this->sgjd;
    }

    /**
     * Set xkzh
     *
     * @param string $xkzh
     * @return Build
     */
    public function setXkzh($xkzh)
    {
        $this->xkzh = $xkzh;
    
        return $this;
    }

    /**
     * Get xkzh
     *
     * @return string 
     */
    public function getXkzh()
    {
        return $this->xkzh;
    }

    /**
     * Set shapan
     *
     * @param string $shapan
     * @return Build
     */
    public function setShapan($shapan)
    {
        $this->shapan = $shapan;
    
        return $this;
    }

    /**
     * Get shapan
     *
     * @return string 
     */
    public function getShapan()
    {
        return $this->shapan;
    }

    /**
     * Set aid
     *
     * @param integer $aid
     * @return Build
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
     * Set models
     *
     * @param string $models
     * @return Build
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
     * @return Build
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
     * Set seat
     *
     * @param string $seat
     * @return Build
     */
    public function setSeat($seat)
    {
        $this->seat = $seat;
    
        return $this;
    }

    /**
     * Get seat
     *
     * @return string 
     */
    public function getSeat()
    {
        return $this->seat;
    }

    /**
     * Set xkzh_time
     *
     * @param \DateTime $xkzhTime
     * @return Build
     */
    public function setXkzhTime($xkzhTime)
    {
        $this->xkzh_time = $xkzhTime;
    
        return $this;
    }

    /**
     * Get xkzh_time
     *
     * @return \DateTime 
     */
    public function getXkzhTime()
    {
        return $this->xkzh_time;
    }

    /**
     * Set build_year
     *
     * @param string $buildYear
     * @return Build
     */
    public function setBuildYear($buildYear)
    {
        $this->build_year = $buildYear;
    
        return $this;
    }

    /**
     * Get build_year
     *
     * @return string 
     */
    public function getBuildYear()
    {
        return $this->build_year;
    }

    /**
     * Set house_num
     *
     * @param integer $houseNum
     * @return Build
     */
    public function setHouseNum($houseNum)
    {
        $this->house_num = $houseNum;
    
        return $this;
    }

    /**
     * Get house_num
     *
     * @return integer 
     */
    public function getHouseNum()
    {
        return $this->house_num;
    }

    /**
     * Set vacancy_floor
     *
     * @param string $vacancyFloor
     * @return Build
     */
    public function setVacancyFloor($vacancyFloor)
    {
        $this->vacancy_floor = $vacancyFloor;
    
        return $this;
    }

    /**
     * Get vacancy_floor
     *
     * @return string 
     */
    public function getVacancyFloor()
    {
        return $this->vacancy_floor;
    }

    /**
     * Set elevator
     *
     * @param integer $elevator
     * @return Build
     */
    public function setElevator($elevator)
    {
        $this->elevator = $elevator;
    
        return $this;
    }

    /**
     * Get elevator
     *
     * @return integer 
     */
    public function getElevator()
    {
        return $this->elevator;
    }

    /**
     * Set door_id
     *
     * @param string $doorId
     * @return Build
     */
    public function setDoorId($doorId)
    {
        $this->door_id = $doorId;
    
        return $this;
    }

    /**
     * Get door_id
     *
     * @return string 
     */
    public function getDoorId()
    {
        return $this->door_id;
    }

    /**
     * Set build_type
     *
     * @param integer $buildType
     * @return Build
     */
    public function setBuildType($buildType)
    {
        $this->build_type = $buildType;
    
        return $this;
    }

    /**
     * Get build_type
     *
     * @return integer 
     */
    public function getBuildType()
    {
        return $this->build_type;
    }

    /**
     * Set ladder
     *
     * @param string $ladder
     * @return Build
     */
    public function setLadder($ladder)
    {
        $this->ladder = $ladder;
    
        return $this;
    }

    /**
     * Get ladder
     *
     * @return string 
     */
    public function getLadder()
    {
        return $this->ladder;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Build
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
     * @return Build
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
     * @return Build
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
     * @return Build
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
     * @return Build
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
     * @return Build
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
     * @return Build
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
     * @return Build
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
