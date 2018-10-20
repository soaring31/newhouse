<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupbuy
 *
 * @ORM\Table(name="groupbuy")
 * @ORM\Entity
 */
class Groupbuy
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
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="ksnum", type="integer", length=10, nullable=false)
     */
    private $ksnum;

    /**
     * @var string
     *
     * @ORM\Column(name="scj", type="string", length=100, nullable=false)
     */
    private $scj;

    /**
     * @var string
     *
     * @ORM\Column(name="tgj", type="string", length=100, nullable=false)
     */
    private $tgj;

    /**
     * @var string
     *
     * @ORM\Column(name="hdadr", type="string", length=100, nullable=false)
     */
    private $hdadr;

    /**
     * @var integer
     *
     * @ORM\Column(name="hdnum", type="integer", length=10, nullable=false)
     */
    private $hdnum;

    /**
     * @var string
     *
     * @ORM\Column(name="yhsm", type="string", length=100, nullable=false)
     */
    private $yhsm;

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
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="date", length=10, nullable=false)
     */
    private $enddate;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

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
     * @ORM\Column(name="area", type="integer", length=100, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=10, nullable=false)
     */
    private $clicks;

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
     * @return Groupbuy
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
     * Set thumb
     *
     * @param string $thumb
     * @return Groupbuy
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
     * Set content
     *
     * @param string $content
     * @return Groupbuy
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
     * Set tel
     *
     * @param string $tel
     * @return Groupbuy
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
     * @param integer $checked
     * @return Groupbuy
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
     * Set ksnum
     *
     * @param integer $ksnum
     * @return Groupbuy
     */
    public function setKsnum($ksnum)
    {
        $this->ksnum = $ksnum;
    
        return $this;
    }

    /**
     * Get ksnum
     *
     * @return integer 
     */
    public function getKsnum()
    {
        return $this->ksnum;
    }

    /**
     * Set scj
     *
     * @param string $scj
     * @return Groupbuy
     */
    public function setScj($scj)
    {
        $this->scj = $scj;
    
        return $this;
    }

    /**
     * Get scj
     *
     * @return string 
     */
    public function getScj()
    {
        return $this->scj;
    }

    /**
     * Set tgj
     *
     * @param string $tgj
     * @return Groupbuy
     */
    public function setTgj($tgj)
    {
        $this->tgj = $tgj;
    
        return $this;
    }

    /**
     * Get tgj
     *
     * @return string 
     */
    public function getTgj()
    {
        return $this->tgj;
    }

    /**
     * Set hdadr
     *
     * @param string $hdadr
     * @return Groupbuy
     */
    public function setHdadr($hdadr)
    {
        $this->hdadr = $hdadr;
    
        return $this;
    }

    /**
     * Get hdadr
     *
     * @return string 
     */
    public function getHdadr()
    {
        return $this->hdadr;
    }

    /**
     * Set hdnum
     *
     * @param integer $hdnum
     * @return Groupbuy
     */
    public function setHdnum($hdnum)
    {
        $this->hdnum = $hdnum;
    
        return $this;
    }

    /**
     * Get hdnum
     *
     * @return integer 
     */
    public function getHdnum()
    {
        return $this->hdnum;
    }

    /**
     * Set yhsm
     *
     * @param string $yhsm
     * @return Groupbuy
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
     * Set keywords
     *
     * @param string $keywords
     * @return Groupbuy
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
     * @return Groupbuy
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
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Groupbuy
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
     * Set models
     *
     * @param string $models
     * @return Groupbuy
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
     * Set aid
     *
     * @param integer $aid
     * @return Groupbuy
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
     * @return Groupbuy
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
     * Set area
     *
     * @param integer $area
     * @return Groupbuy
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
     * Set clicks
     *
     * @param integer $clicks
     * @return Groupbuy
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
    
        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer 
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Groupbuy
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
     * @return Groupbuy
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
     * @return Groupbuy
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
     * @return Groupbuy
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
     * @return Groupbuy
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
     * @return Groupbuy
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
     * @return Groupbuy
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
