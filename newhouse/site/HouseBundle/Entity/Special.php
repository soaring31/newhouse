<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Special
 *
 * @ORM\Table(name="special")
 * @ORM\Entity
 */
class Special
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
     * @var string
     *
     * @ORM\Column(name="lpmc", type="string", length=100, nullable=false)
     */
    private $lpmc;

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
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=100, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=100, nullable=false)
     */
    private $abstract;

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
     * @ORM\Column(name="cate_line", type="integer", length=100, nullable=false)
     */
    private $cate_line;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_metro", type="integer", length=100, nullable=false)
     */
    private $cate_metro;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=100, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="lh", type="string", length=100, nullable=false)
     */
    private $lh;

    /**
     * @var string
     *
     * @ORM\Column(name="fh", type="string", length=100, nullable=false)
     */
    private $fh;

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
     * @var float
     *
     * @ORM\Column(name="zj", type="float", length=100, nullable=false)
     */
    private $zj;

    /**
     * @var float
     *
     * @ORM\Column(name="yj", type="float", length=100, nullable=false)
     */
    private $yj;

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
     * @var integer
     *
     * @ORM\Column(name="zxcd", type="smallint", length=2, nullable=false)
     */
    private $zxcd;

    /**
     * @var integer
     *
     * @ORM\Column(name="cx", type="integer", length=10, nullable=false)
     */
    private $cx;

    /**
     * @var string
     *
     * @ORM\Column(name="szlc", type="string", length=100, nullable=false)
     */
    private $szlc;

    /**
     * @var string
     *
     * @ORM\Column(name="zlc", type="string", length=100, nullable=false)
     */
    private $zlc;

    /**
     * @var integer
     *
     * @ORM\Column(name="louxing", type="smallint", length=2, nullable=false)
     */
    private $louxing;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_status", type="smallint", length=2, nullable=false)
     */
    private $cate_status;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="xingming", type="string", length=100, nullable=false)
     */
    private $xingming;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
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
     * @return Special
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
     * @return Special
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
     * Set lpmc
     *
     * @param string $lpmc
     * @return Special
     */
    public function setLpmc($lpmc)
    {
        $this->lpmc = $lpmc;
    
        return $this;
    }

    /**
     * Get lpmc
     *
     * @return string 
     */
    public function getLpmc()
    {
        return $this->lpmc;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Special
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
     * @return Special
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
     * Set keywords
     *
     * @param string $keywords
     * @return Special
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
     * Set abstract
     *
     * @param string $abstract
     * @return Special
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
     * Set region
     *
     * @param integer $region
     * @return Special
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
     * @return Special
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
     * Set cate_line
     *
     * @param integer $cateLine
     * @return Special
     */
    public function setCateLine($cateLine)
    {
        $this->cate_line = $cateLine;
    
        return $this;
    }

    /**
     * Get cate_line
     *
     * @return integer 
     */
    public function getCateLine()
    {
        return $this->cate_line;
    }

    /**
     * Set cate_metro
     *
     * @param integer $cateMetro
     * @return Special
     */
    public function setCateMetro($cateMetro)
    {
        $this->cate_metro = $cateMetro;
    
        return $this;
    }

    /**
     * Get cate_metro
     *
     * @return integer 
     */
    public function getCateMetro()
    {
        return $this->cate_metro;
    }

    /**
     * Set area
     *
     * @param integer $area
     * @return Special
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
     * Set lh
     *
     * @param string $lh
     * @return Special
     */
    public function setLh($lh)
    {
        $this->lh = $lh;
    
        return $this;
    }

    /**
     * Get lh
     *
     * @return string 
     */
    public function getLh()
    {
        return $this->lh;
    }

    /**
     * Set fh
     *
     * @param string $fh
     * @return Special
     */
    public function setFh($fh)
    {
        $this->fh = $fh;
    
        return $this;
    }

    /**
     * Get fh
     *
     * @return string 
     */
    public function getFh()
    {
        return $this->fh;
    }

    /**
     * Set dj
     *
     * @param float $dj
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * Set yj
     *
     * @param float $yj
     * @return Special
     */
    public function setYj($yj)
    {
        $this->yj = $yj;
    
        return $this;
    }

    /**
     * Get yj
     *
     * @return float 
     */
    public function getYj()
    {
        return $this->yj;
    }

    /**
     * Set shi
     *
     * @param integer $shi
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * Set zxcd
     *
     * @param integer $zxcd
     * @return Special
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
     * Set cx
     *
     * @param integer $cx
     * @return Special
     */
    public function setCx($cx)
    {
        $this->cx = $cx;
    
        return $this;
    }

    /**
     * Get cx
     *
     * @return integer 
     */
    public function getCx()
    {
        return $this->cx;
    }

    /**
     * Set szlc
     *
     * @param string $szlc
     * @return Special
     */
    public function setSzlc($szlc)
    {
        $this->szlc = $szlc;
    
        return $this;
    }

    /**
     * Get szlc
     *
     * @return string 
     */
    public function getSzlc()
    {
        return $this->szlc;
    }

    /**
     * Set zlc
     *
     * @param string $zlc
     * @return Special
     */
    public function setZlc($zlc)
    {
        $this->zlc = $zlc;
    
        return $this;
    }

    /**
     * Get zlc
     *
     * @return string 
     */
    public function getZlc()
    {
        return $this->zlc;
    }

    /**
     * Set louxing
     *
     * @param integer $louxing
     * @return Special
     */
    public function setLouxing($louxing)
    {
        $this->louxing = $louxing;
    
        return $this;
    }

    /**
     * Get louxing
     *
     * @return integer 
     */
    public function getLouxing()
    {
        return $this->louxing;
    }

    /**
     * Set cate_status
     *
     * @param integer $cateStatus
     * @return Special
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
     * Set thumb
     *
     * @param string $thumb
     * @return Special
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
     * Set xingming
     *
     * @param string $xingming
     * @return Special
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
     * Set tel
     *
     * @param string $tel
     * @return Special
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
     * Set checked
     *
     * @param boolean $checked
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
     * @return Special
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
