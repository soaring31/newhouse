<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataImplog
 *
 * @ORM\Table(name="data_implog")
 * @ORM\Entity
 */
class DataImplog
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
     * @ORM\Column(name="nid", type="integer", length=10, nullable=false)
     */
    private $nid;

    /**
     * @var string
     *
     * @ORM\Column(name="nsvid", type="string", length=48, nullable=false)
     */
    private $nsvid;

    /**
     * @var integer
     *
     * @ORM\Column(name="nupd", type="integer", length=10, nullable=false)
     */
    private $nupd;

    /**
     * @var integer
     *
     * @ORM\Column(name="oid", type="integer", length=10, nullable=false)
     */
    private $oid;

    /**
     * @var string
     *
     * @ORM\Column(name="omod", type="string", length=24, nullable=false)
     */
    private $omod;

    /**
     * @var string
     *
     * @ORM\Column(name="ocfgs", type="text", length=1200, nullable=false)
     */
    private $ocfgs;

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
     * Set nid
     *
     * @param integer $nid
     * @return DataImplog
     */
    public function setNid($nid)
    {
        $this->nid = $nid;
    
        return $this;
    }

    /**
     * Get nid
     *
     * @return integer 
     */
    public function getNid()
    {
        return $this->nid;
    }

    /**
     * Set nsvid
     *
     * @param string $nsvid
     * @return DataImplog
     */
    public function setNsvid($nsvid)
    {
        $this->nsvid = $nsvid;
    
        return $this;
    }

    /**
     * Get nsvid
     *
     * @return string 
     */
    public function getNsvid()
    {
        return $this->nsvid;
    }

    /**
     * Set nupd
     *
     * @param integer $nupd
     * @return DataImplog
     */
    public function setNupd($nupd)
    {
        $this->nupd = $nupd;
    
        return $this;
    }

    /**
     * Get nupd
     *
     * @return integer 
     */
    public function getNupd()
    {
        return $this->nupd;
    }

    /**
     * Set oid
     *
     * @param integer $oid
     * @return DataImplog
     */
    public function setOid($oid)
    {
        $this->oid = $oid;
    
        return $this;
    }

    /**
     * Get oid
     *
     * @return integer 
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set omod
     *
     * @param string $omod
     * @return DataImplog
     */
    public function setOmod($omod)
    {
        $this->omod = $omod;
    
        return $this;
    }

    /**
     * Get omod
     *
     * @return string 
     */
    public function getOmod()
    {
        return $this->omod;
    }

    /**
     * Set ocfgs
     *
     * @param string $ocfgs
     * @return DataImplog
     */
    public function setOcfgs($ocfgs)
    {
        $this->ocfgs = $ocfgs;
    
        return $this;
    }

    /**
     * Get ocfgs
     *
     * @return string 
     */
    public function getOcfgs()
    {
        return $this->ocfgs;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return DataImplog
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
     * @return DataImplog
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
     * @return DataImplog
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
     * @return DataImplog
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
     * @return DataImplog
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
     * @return DataImplog
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
     * @return DataImplog
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
     * @return DataImplog
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
