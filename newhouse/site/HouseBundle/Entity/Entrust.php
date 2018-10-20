<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrust
 *
 * @ORM\Table(name="entrust", indexes={@ORM\Index(name="entrust", columns={"entrust"})})
 * @ORM\Entity
 */
class Entrust
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
     * @ORM\Column(name="yang", type="smallint", length=2, nullable=false)
     */
    private $yang;

    /**
     * @var integer
     *
     * @ORM\Column(name="mj", type="integer", length=10, nullable=false)
     */
    private $mj;

    /**
     * @var integer
     *
     * @ORM\Column(name="dj", type="integer", length=10, nullable=false)
     */
    private $dj;

    /**
     * @var integer
     *
     * @ORM\Column(name="zj", type="integer", length=10, nullable=false)
     */
    private $zj;

    /**
     * @var string
     *
     * @ORM\Column(name="xingming", type="string", length=100, nullable=false)
     */
    private $xingming;

    /**
     * @var string
     *
     * @ORM\Column(name="fdtel", type="string", length=11, nullable=false)
     */
    private $fdtel;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="entrust", type="smallint", length=2, nullable=false)
     */
    private $entrust;

    /**
     * @var string
     *
     * @ORM\Column(name="wid", type="string", length=100, nullable=false)
     */
    private $wid;

    /**
     * @var integer
     *
     * @ORM\Column(name="fid", type="integer", length=10, nullable=false)
     */
    private $fid;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=10, nullable=false)
     */
    private $area;

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
     * @return Entrust
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
     * Set shi
     *
     * @param integer $shi
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
     * Set yang
     *
     * @param integer $yang
     * @return Entrust
     */
    public function setYang($yang)
    {
        $this->yang = $yang;
    
        return $this;
    }

    /**
     * Get yang
     *
     * @return integer 
     */
    public function getYang()
    {
        return $this->yang;
    }

    /**
     * Set mj
     *
     * @param integer $mj
     * @return Entrust
     */
    public function setMj($mj)
    {
        $this->mj = $mj;
    
        return $this;
    }

    /**
     * Get mj
     *
     * @return integer 
     */
    public function getMj()
    {
        return $this->mj;
    }

    /**
     * Set dj
     *
     * @param integer $dj
     * @return Entrust
     */
    public function setDj($dj)
    {
        $this->dj = $dj;
    
        return $this;
    }

    /**
     * Get dj
     *
     * @return integer 
     */
    public function getDj()
    {
        return $this->dj;
    }

    /**
     * Set zj
     *
     * @param integer $zj
     * @return Entrust
     */
    public function setZj($zj)
    {
        $this->zj = $zj;
    
        return $this;
    }

    /**
     * Get zj
     *
     * @return integer 
     */
    public function getZj()
    {
        return $this->zj;
    }

    /**
     * Set xingming
     *
     * @param string $xingming
     * @return Entrust
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
     * Set fdtel
     *
     * @param string $fdtel
     * @return Entrust
     */
    public function setFdtel($fdtel)
    {
        $this->fdtel = $fdtel;
    
        return $this;
    }

    /**
     * Get fdtel
     *
     * @return string 
     */
    public function getFdtel()
    {
        return $this->fdtel;
    }

    /**
     * Set models
     *
     * @param string $models
     * @return Entrust
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
     * Set entrust
     *
     * @param integer $entrust
     * @return Entrust
     */
    public function setEntrust($entrust)
    {
        $this->entrust = $entrust;
    
        return $this;
    }

    /**
     * Get entrust
     *
     * @return integer 
     */
    public function getEntrust()
    {
        return $this->entrust;
    }

    /**
     * Set wid
     *
     * @param string $wid
     * @return Entrust
     */
    public function setWid($wid)
    {
        $this->wid = $wid;
    
        return $this;
    }

    /**
     * Get wid
     *
     * @return string 
     */
    public function getWid()
    {
        return $this->wid;
    }

    /**
     * Set fid
     *
     * @param integer $fid
     * @return Entrust
     */
    public function setFid($fid)
    {
        $this->fid = $fid;
    
        return $this;
    }

    /**
     * Get fid
     *
     * @return integer 
     */
    public function getFid()
    {
        return $this->fid;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Entrust
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
     * Set area
     *
     * @param integer $area
     * @return Entrust
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
     * Set attributes
     *
     * @param string $attributes
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
     * @return Entrust
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
