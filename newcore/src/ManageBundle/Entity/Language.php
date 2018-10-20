<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity
 */
class Language
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
     * @ORM\Column(name="ename", type="string", length=100, nullable=false)
     */
    private $ename;

    /**
     * @var string
     *
     * @ORM\Column(name="tplid", type="string", length=100, nullable=false)
     */
    private $tplid;

    /**
     * @var string
     *
     * @ORM\Column(name="userdb", type="string", length=100, nullable=false)
     */
    private $userdb;

    /**
     * @var integer
     *
     * @ORM\Column(name="themetype", type="integer", length=10, nullable=false)
     */
    private $themetype;

    /**
     * @var string
     *
     * @ORM\Column(name="membertpl", type="string", length=100, nullable=false)
     */
    private $membertpl;

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
     * @return Language
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
     * Set ename
     *
     * @param string $ename
     * @return Language
     */
    public function setEname($ename)
    {
        $this->ename = $ename;
    
        return $this;
    }

    /**
     * Get ename
     *
     * @return string 
     */
    public function getEname()
    {
        return $this->ename;
    }

    /**
     * Set tplid
     *
     * @param string $tplid
     * @return Language
     */
    public function setTplid($tplid)
    {
        $this->tplid = $tplid;
    
        return $this;
    }

    /**
     * Get tplid
     *
     * @return string 
     */
    public function getTplid()
    {
        return $this->tplid;
    }

    /**
     * Set userdb
     *
     * @param string $userdb
     * @return Language
     */
    public function setUserdb($userdb)
    {
        $this->userdb = $userdb;
    
        return $this;
    }

    /**
     * Get userdb
     *
     * @return string 
     */
    public function getUserdb()
    {
        return $this->userdb;
    }

    /**
     * Set themetype
     *
     * @param integer $themetype
     * @return Language
     */
    public function setThemetype($themetype)
    {
        $this->themetype = $themetype;
    
        return $this;
    }

    /**
     * Get themetype
     *
     * @return integer 
     */
    public function getThemetype()
    {
        return $this->themetype;
    }

    /**
     * Set membertpl
     *
     * @param string $membertpl
     * @return Language
     */
    public function setMembertpl($membertpl)
    {
        $this->membertpl = $membertpl;
    
        return $this;
    }

    /**
     * Get membertpl
     *
     * @return string 
     */
    public function getMembertpl()
    {
        return $this->membertpl;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Language
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
     * @return Language
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
     * @return Language
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
     * @return Language
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
     * @return Language
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
     * @return Language
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
     * @return Language
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
     * @return Language
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
