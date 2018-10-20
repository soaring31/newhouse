<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Door
 *
 * @ORM\Table(name="door", indexes={@ORM\Index(name="aid", columns={"aid"}), @ORM\Index(name="area", columns={"area"}), @ORM\Index(name="shi", columns={"shi"})})
 * @ORM\Entity
 */
class Door
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
     * @ORM\Column(name="aid", type="integer", length=100, nullable=false)
     */
    private $aid;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=100, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=100, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", length=100, nullable=false)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_circle", type="integer", length=100, nullable=false)
     */
    private $cate_circle;

    /**
     * @var integer
     *
     * @ORM\Column(name="zlhx", type="smallint", length=2, nullable=false)
     */
    private $zlhx;

    /**
     * @var float
     *
     * @ORM\Column(name="dj", type="float", length=100, nullable=false)
     */
    private $dj;

    /**
     * @var float
     *
     * @ORM\Column(name="mj", type="float", length=100, nullable=false)
     */
    private $mj;

    /**
     * @var integer
     *
     * @ORM\Column(name="shi", type="smallint", length=2, nullable=false)
     */
    private $shi;

    /**
     * @var integer
     *
     * @ORM\Column(name="ting", type="smallint", length=2, nullable=false)
     */
    private $ting;

    /**
     * @var integer
     *
     * @ORM\Column(name="wei", type="smallint", length=2, nullable=false)
     */
    private $wei;

    /**
     * @var integer
     *
     * @ORM\Column(name="chu", type="smallint", length=2, nullable=false)
     */
    private $chu;

    /**
     * @var integer
     *
     * @ORM\Column(name="yangtai", type="smallint", length=2, nullable=false)
     */
    private $yangtai;

    /**
     * @var string
     *
     * @ORM\Column(name="cate_type", type="string", length=100, nullable=false)
     */
    private $cate_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_status", type="smallint", length=2, nullable=false)
     */
    private $cate_status;

    /**
     * @var integer
     *
     * @ORM\Column(name="ss_build", type="smallint", length=2, nullable=false)
     */
    private $ss_build;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="tujis", type="text", length=0, nullable=false)
     */
    private $tujis;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", length=0, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="house_toward", type="string", length=50, nullable=false)
     */
    private $house_toward;

    /**
     * @var float
     *
     * @ORM\Column(name="reference_totalprice", type="float", length=11, nullable=false)
     */
    private $reference_totalprice;

    /**
     * @var float
     *
     * @ORM\Column(name="reference_down_payment", type="float", length=11, nullable=false)
     */
    private $reference_down_payment;

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
     * @return Door
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
     * @return Door
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
     * Set uid
     *
     * @param integer $uid
     * @return Door
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
     * Set models
     *
     * @param string $models
     * @return Door
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
     * Set area
     *
     * @param integer $area
     * @return Door
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
     * Set region
     *
     * @param integer $region
     * @return Door
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
     * Set cate_circle
     *
     * @param integer $cateCircle
     * @return Door
     */
    public function setCateCircle($cateCircle)
    {
        $this->cate_circle = $cateCircle;

        return $this;
    }

    /**
     * Get cate_circle
     *
     * @return integer 
     */
    public function getCateCircle()
    {
        return $this->cate_circle;
    }

    /**
     * Set zlhx
     *
     * @param integer $zlhx
     * @return Door
     */
    public function setZlhx($zlhx)
    {
        $this->zlhx = $zlhx;

        return $this;
    }

    /**
     * Get zlhx
     *
     * @return integer 
     */
    public function getZlhx()
    {
        return $this->zlhx;
    }

    /**
     * Set dj
     *
     * @param float $dj
     * @return Door
     */
    public function setDj($dj)
    {
        $this->dj = $dj;

        return $this;
    }

    /**
     * Get dj
     *
     * @return float 
     */
    public function getDj()
    {
        return $this->dj;
    }

    /**
     * Set mj
     *
     * @param float $mj
     * @return Door
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
     * Set shi
     *
     * @param integer $shi
     * @return Door
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
     * @return Door
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
     * Set wei
     *
     * @param integer $wei
     * @return Door
     */
    public function setWei($wei)
    {
        $this->wei = $wei;

        return $this;
    }

    /**
     * Get wei
     *
     * @return integer 
     */
    public function getWei()
    {
        return $this->wei;
    }

    /**
     * Set chu
     *
     * @param integer $chu
     * @return Door
     */
    public function setChu($chu)
    {
        $this->chu = $chu;

        return $this;
    }

    /**
     * Get chu
     *
     * @return integer 
     */
    public function getChu()
    {
        return $this->chu;
    }

    /**
     * Set yangtai
     *
     * @param integer $yangtai
     * @return Door
     */
    public function setYangtai($yangtai)
    {
        $this->yangtai = $yangtai;

        return $this;
    }

    /**
     * Get yangtai
     *
     * @return integer 
     */
    public function getYangtai()
    {
        return $this->yangtai;
    }

    /**
     * Set cate_type
     *
     * @param string $cateType
     * @return Door
     */
    public function setCateType($cateType)
    {
        $this->cate_type = $cateType;

        return $this;
    }

    /**
     * Get cate_type
     *
     * @return string 
     */
    public function getCateType()
    {
        return $this->cate_type;
    }

    /**
     * Set cate_status
     *
     * @param integer $cateStatus
     * @return Door
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
     * Set ss_build
     *
     * @param integer $ssBuild
     * @return Door
     */
    public function setSsBuild($ssBuild)
    {
        $this->ss_build = $ssBuild;

        return $this;
    }

    /**
     * Get ss_build
     *
     * @return integer 
     */
    public function getSsBuild()
    {
        return $this->ss_build;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return Door
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
     * Set tujis
     *
     * @param string $tujis
     * @return Door
     */
    public function setTujis($tujis)
    {
        $this->tujis = $tujis;

        return $this;
    }

    /**
     * Get tujis
     *
     * @return string 
     */
    public function getTujis()
    {
        return $this->tujis;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return Door
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
     * Set house_toward
     *
     * @param string $houseToward
     * @return Door
     */
    public function setHouseToward($houseToward)
    {
        $this->house_toward = $houseToward;

        return $this;
    }

    /**
     * Get house_toward
     *
     * @return string 
     */
    public function getHouseToward()
    {
        return $this->house_toward;
    }

    /**
     * Set reference_totalprice
     *
     * @param float $referenceTotalprice
     * @return Door
     */
    public function setReferenceTotalprice($referenceTotalprice)
    {
        $this->reference_totalprice = $referenceTotalprice;

        return $this;
    }

    /**
     * Get reference_totalprice
     *
     * @return float 
     */
    public function getReferenceTotalprice()
    {
        return $this->reference_totalprice;
    }

    /**
     * Set reference_down_payment
     *
     * @param float $referenceDownPayment
     * @return Door
     */
    public function setReferenceDownPayment($referenceDownPayment)
    {
        $this->reference_down_payment = $referenceDownPayment;

        return $this;
    }

    /**
     * Get reference_down_payment
     *
     * @return float 
     */
    public function getReferenceDownPayment()
    {
        return $this->reference_down_payment;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Door
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
     * @return Door
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
     * @return Door
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
     * @return Door
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
     * @return Door
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
     * @return Door
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
     * @return Door
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
     * @return Door
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
