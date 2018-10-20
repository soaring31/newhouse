<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxhardware
 *
 * @ORM\Table(name="wxhardware")
 * @ORM\Entity
 */
class Wxhardware
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
     * @ORM\Column(name="storetitle", type="string", length=50, nullable=false)
     */
    private $storetitle;

    /**
     * @var string
     *
     * @ORM\Column(name="wifititle", type="string", length=50, nullable=false)
     */
    private $wifititle;

    /**
     * @var string
     *
     * @ORM\Column(name="wifipw", type="string", length=50, nullable=false)
     */
    private $wifipw;

    /**
     * @var string
     *
     * @ORM\Column(name="macaddress", type="string", length=100, nullable=false)
     */
    private $macaddress;

    /**
     * @var string
     *
     * @ORM\Column(name="routerid", type="string", length=100, nullable=false)
     */
    private $routerid;

    /**
     * @var string
     *
     * @ORM\Column(name="routertitle", type="string", length=50, nullable=false)
     */
    private $routertitle;

    /**
     * @var string
     *
     * @ORM\Column(name="openid", type="string", length=50, nullable=false)
     */
    private $openid;

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
     * Set storetitle
     *
     * @param string $storetitle
     * @return Wxhardware
     */
    public function setStoretitle($storetitle)
    {
        $this->storetitle = $storetitle;
    
        return $this;
    }

    /**
     * Get storetitle
     *
     * @return string 
     */
    public function getStoretitle()
    {
        return $this->storetitle;
    }

    /**
     * Set wifititle
     *
     * @param string $wifititle
     * @return Wxhardware
     */
    public function setWifititle($wifititle)
    {
        $this->wifititle = $wifititle;
    
        return $this;
    }

    /**
     * Get wifititle
     *
     * @return string 
     */
    public function getWifititle()
    {
        return $this->wifititle;
    }

    /**
     * Set wifipw
     *
     * @param string $wifipw
     * @return Wxhardware
     */
    public function setWifipw($wifipw)
    {
        $this->wifipw = $wifipw;
    
        return $this;
    }

    /**
     * Get wifipw
     *
     * @return string 
     */
    public function getWifipw()
    {
        return $this->wifipw;
    }

    /**
     * Set macaddress
     *
     * @param string $macaddress
     * @return Wxhardware
     */
    public function setMacaddress($macaddress)
    {
        $this->macaddress = $macaddress;
    
        return $this;
    }

    /**
     * Get macaddress
     *
     * @return string 
     */
    public function getMacaddress()
    {
        return $this->macaddress;
    }

    /**
     * Set routerid
     *
     * @param string $routerid
     * @return Wxhardware
     */
    public function setRouterid($routerid)
    {
        $this->routerid = $routerid;
    
        return $this;
    }

    /**
     * Get routerid
     *
     * @return string 
     */
    public function getRouterid()
    {
        return $this->routerid;
    }

    /**
     * Set routertitle
     *
     * @param string $routertitle
     * @return Wxhardware
     */
    public function setRoutertitle($routertitle)
    {
        $this->routertitle = $routertitle;
    
        return $this;
    }

    /**
     * Get routertitle
     *
     * @return string 
     */
    public function getRoutertitle()
    {
        return $this->routertitle;
    }

    /**
     * Set openid
     *
     * @param string $openid
     * @return Wxhardware
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;
    
        return $this;
    }

    /**
     * Get openid
     *
     * @return string 
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxhardware
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
     * @return Wxhardware
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
     * @return Wxhardware
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
     * @return Wxhardware
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
     * @return Wxhardware
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
     * @return Wxhardware
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
     * @return Wxhardware
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
     * @return Wxhardware
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
