<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sell
 *
 * @ORM\Table(name="sell")
 * @ORM\Entity
 */
class Sell
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
     * @ORM\Column(name="keywords", type="string", length=100, nullable=false)
     */
    private $keywords;

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
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=100, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="kpsj", type="date", length=10, nullable=false)
     */
    private $kpsj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="date", length=10, nullable=false)
     */
    private $enddate;

    /**
     * @var string
     *
     * @ORM\Column(name="yhsm", type="string", length=100, nullable=false)
     */
    private $yhsm;

    /**
     * @var integer
     *
     * @ORM\Column(name="yongjin", type="integer", length=100, nullable=false)
     */
    private $yongjin;

    /**
     * @var integer
     *
     * @ORM\Column(name="yiyuding", type="integer", length=100, nullable=false)
     */
    private $yiyuding;

    /**
     * @var integer
     *
     * @ORM\Column(name="yituijian", type="integer", length=100, nullable=false)
     */
    private $yituijian;

    /**
     * @var integer
     *
     * @ORM\Column(name="yichengjiao", type="integer", length=100, nullable=false)
     */
    private $yichengjiao;

    /**
     * @var integer
     *
     * @ORM\Column(name="shichangjia", type="integer", length=100, nullable=false)
     */
    private $shichangjia;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=100, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=100, nullable=false)
     */
    private $area;

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
     * @return Sell
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
     * Set keywords
     *
     * @param string $keywords
     * @return Sell
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
     * Set aid
     *
     * @param integer $aid
     * @return Sell
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
     * @return Sell
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
     * Set models
     *
     * @param string $models
     * @return Sell
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
     * Set abstract
     *
     * @param string $abstract
     * @return Sell
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
     * @return Sell
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
     * Set kpsj
     *
     * @param \DateTime $kpsj
     * @return Sell
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
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Sell
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    
        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set yhsm
     *
     * @param string $yhsm
     * @return Sell
     */
    public function setYhsm($yhsm)
    {
        $this->yhsm = $yhsm;
    
        return $this;
    }

    /**
     * Get yhsm
     *
     * @return string 
     */
    public function getYhsm()
    {
        return $this->yhsm;
    }

    /**
     * Set yongjin
     *
     * @param integer $yongjin
     * @return Sell
     */
    public function setYongjin($yongjin)
    {
        $this->yongjin = $yongjin;
    
        return $this;
    }

    /**
     * Get yongjin
     *
     * @return integer 
     */
    public function getYongjin()
    {
        return $this->yongjin;
    }

    /**
     * Set yiyuding
     *
     * @param integer $yiyuding
     * @return Sell
     */
    public function setYiyuding($yiyuding)
    {
        $this->yiyuding = $yiyuding;
    
        return $this;
    }

    /**
     * Get yiyuding
     *
     * @return integer 
     */
    public function getYiyuding()
    {
        return $this->yiyuding;
    }

    /**
     * Set yituijian
     *
     * @param integer $yituijian
     * @return Sell
     */
    public function setYituijian($yituijian)
    {
        $this->yituijian = $yituijian;
    
        return $this;
    }

    /**
     * Get yituijian
     *
     * @return integer 
     */
    public function getYituijian()
    {
        return $this->yituijian;
    }

    /**
     * Set yichengjiao
     *
     * @param integer $yichengjiao
     * @return Sell
     */
    public function setYichengjiao($yichengjiao)
    {
        $this->yichengjiao = $yichengjiao;
    
        return $this;
    }

    /**
     * Get yichengjiao
     *
     * @return integer 
     */
    public function getYichengjiao()
    {
        return $this->yichengjiao;
    }

    /**
     * Set shichangjia
     *
     * @param integer $shichangjia
     * @return Sell
     */
    public function setShichangjia($shichangjia)
    {
        $this->shichangjia = $shichangjia;
    
        return $this;
    }

    /**
     * Get shichangjia
     *
     * @return integer 
     */
    public function getShichangjia()
    {
        return $this->shichangjia;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Sell
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
     * Set area
     *
     * @param integer $area
     * @return Sell
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
     * Set checked
     *
     * @param boolean $checked
     * @return Sell
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
     * @return Sell
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
     * @return Sell
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
     * @return Sell
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
     * @return Sell
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
     * @return Sell
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
     * @return Sell
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
     * @return Sell
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
