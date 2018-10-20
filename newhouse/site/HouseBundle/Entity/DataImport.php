<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataImport
 *
 * @ORM\Table(name="data_import")
 * @ORM\Entity
 */
class DataImport
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
     * @ORM\Column(name="dbcfg", type="string", length=255, nullable=false)
     */
    private $dbcfg;

    /**
     * @var string
     *
     * @ORM\Column(name="dbsql", type="text", length=255, nullable=false)
     */
    private $dbsql;

    /**
     * @var string
     *
     * @ORM\Column(name="dbkey", type="string", length=48, nullable=false)
     */
    private $dbkey;

    /**
     * @var string
     *
     * @ORM\Column(name="insvid", type="string", length=48, nullable=false)
     */
    private $insvid;

    /**
     * @var string
     *
     * @ORM\Column(name="inform", type="string", length=12, nullable=false)
     */
    private $inform;

    /**
     * @var integer
     *
     * @ORM\Column(name="oplimit", type="integer", length=10, nullable=false)
     */
    private $oplimit = '100';

    /**
     * @var string
     *
     * @ORM\Column(name="charset", type="string", length=24, nullable=false)
     */
    private $charset;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=24, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fromsql", type="string", length=255, nullable=false)
     */
    private $fromsql;

    /**
     * @var string
     *
     * @ORM\Column(name="omod", type="string", length=24, nullable=false)
     */
    private $omod;

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
     * Set dbcfg
     *
     * @param string $dbcfg
     * @return DataImport
     */
    public function setDbcfg($dbcfg)
    {
        $this->dbcfg = $dbcfg;
    
        return $this;
    }

    /**
     * Get dbcfg
     *
     * @return string 
     */
    public function getDbcfg()
    {
        return $this->dbcfg;
    }

    /**
     * Set dbsql
     *
     * @param string $dbsql
     * @return DataImport
     */
    public function setDbsql($dbsql)
    {
        $this->dbsql = $dbsql;
    
        return $this;
    }

    /**
     * Get dbsql
     *
     * @return string 
     */
    public function getDbsql()
    {
        return $this->dbsql;
    }

    /**
     * Set dbkey
     *
     * @param string $dbkey
     * @return DataImport
     */
    public function setDbkey($dbkey)
    {
        $this->dbkey = $dbkey;
    
        return $this;
    }

    /**
     * Get dbkey
     *
     * @return string 
     */
    public function getDbkey()
    {
        return $this->dbkey;
    }

    /**
     * Set insvid
     *
     * @param string $insvid
     * @return DataImport
     */
    public function setInsvid($insvid)
    {
        $this->insvid = $insvid;
    
        return $this;
    }

    /**
     * Get insvid
     *
     * @return string 
     */
    public function getInsvid()
    {
        return $this->insvid;
    }

    /**
     * Set inform
     *
     * @param string $inform
     * @return DataImport
     */
    public function setInform($inform)
    {
        $this->inform = $inform;
    
        return $this;
    }

    /**
     * Get inform
     *
     * @return string 
     */
    public function getInform()
    {
        return $this->inform;
    }

    /**
     * Set oplimit
     *
     * @param integer $oplimit
     * @return DataImport
     */
    public function setOplimit($oplimit)
    {
        $this->oplimit = $oplimit;
    
        return $this;
    }

    /**
     * Get oplimit
     *
     * @return integer 
     */
    public function getOplimit()
    {
        return $this->oplimit;
    }

    /**
     * Set charset
     *
     * @param string $charset
     * @return DataImport
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    
        return $this;
    }

    /**
     * Get charset
     *
     * @return string 
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return DataImport
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
     * Set fromsql
     *
     * @param string $fromsql
     * @return DataImport
     */
    public function setFromsql($fromsql)
    {
        $this->fromsql = $fromsql;
    
        return $this;
    }

    /**
     * Get fromsql
     *
     * @return string 
     */
    public function getFromsql()
    {
        return $this->fromsql;
    }

    /**
     * Set omod
     *
     * @param string $omod
     * @return DataImport
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
     * Set checked
     *
     * @param boolean $checked
     * @return DataImport
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
     * @return DataImport
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
     * @return DataImport
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
     * @return DataImport
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
     * @return DataImport
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
     * @return DataImport
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
     * @return DataImport
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
     * @return DataImport
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
