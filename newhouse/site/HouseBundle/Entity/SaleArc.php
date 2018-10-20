<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SaleArc
 *
 * @ORM\Table(name="sale_arc", indexes={@ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="aid", columns={"aid"})})
 * @ORM\Entity
 */
class SaleArc
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
     * @ORM\Column(name="fromid", type="string", length=100, nullable=false)
     */
    private $fromid;

    /**
     * @var string
     *
     * @ORM\Column(name="cate_pushs", type="string", length=100, nullable=false)
     */
    private $cate_pushs;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_album", type="integer", length=10, nullable=false)
     */
    private $cate_album;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=10, nullable=false)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="jbaotype", type="integer", length=10, nullable=false)
     */
    private $jbaotype;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=100, nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes", type="string", length=100, nullable=false)
     */
    private $attributes;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="text", length=0, nullable=false)
     */
    private $user_agent;

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
     * Set fromid
     *
     * @param string $fromid
     * @return SaleArc
     */
    public function setFromid($fromid)
    {
        $this->fromid = $fromid;
    
        return $this;
    }

    /**
     * Get fromid
     *
     * @return string 
     */
    public function getFromid()
    {
        return $this->fromid;
    }

    /**
     * Set cate_pushs
     *
     * @param string $catePushs
     * @return SaleArc
     */
    public function setCatePushs($catePushs)
    {
        $this->cate_pushs = $catePushs;
    
        return $this;
    }

    /**
     * Get cate_pushs
     *
     * @return string 
     */
    public function getCatePushs()
    {
        return $this->cate_pushs;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SaleArc
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
     * Set tel
     *
     * @param string $tel
     * @return SaleArc
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
     * Set models
     *
     * @param string $models
     * @return SaleArc
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
     * Set thumb
     *
     * @param string $thumb
     * @return SaleArc
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
     * Set cate_album
     *
     * @param integer $cateAlbum
     * @return SaleArc
     */
    public function setCateAlbum($cateAlbum)
    {
        $this->cate_album = $cateAlbum;
    
        return $this;
    }

    /**
     * Get cate_album
     *
     * @return integer 
     */
    public function getCateAlbum()
    {
        return $this->cate_album;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return SaleArc
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
     * Set aid
     *
     * @param integer $aid
     * @return SaleArc
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
     * Set content
     *
     * @param string $content
     * @return SaleArc
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set jbaotype
     *
     * @param integer $jbaotype
     * @return SaleArc
     */
    public function setJbaotype($jbaotype)
    {
        $this->jbaotype = $jbaotype;
    
        return $this;
    }

    /**
     * Get jbaotype
     *
     * @return integer 
     */
    public function getJbaotype()
    {
        return $this->jbaotype;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return SaleArc
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
     * Set value
     *
     * @param string $value
     * @return SaleArc
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return SaleArc
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
     * Set user_agent
     *
     * @param string $userAgent
     * @return SaleArc
     */
    public function setUserAgent($userAgent)
    {
        $this->user_agent = $userAgent;
    
        return $this;
    }

    /**
     * Get user_agent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return SaleArc
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
     * @return SaleArc
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
     * @return SaleArc
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
     * @return SaleArc
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
     * @return SaleArc
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
     * @return SaleArc
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
