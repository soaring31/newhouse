<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table(name="topic", indexes={@ORM\Index(name="category", columns={"category"}), @ORM\Index(name="area", columns={"area"})})
 * @ORM\Entity
 */
class Topic
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
     * @ORM\Column(name="category", type="integer", length=10, nullable=false)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="pid", type="integer", length=10, nullable=false)
     */
    private $pid;

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
     * @ORM\Column(name="abstract", type="string", length=100, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=100, nullable=false)
     */
    private $keywords;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="date", length=20, nullable=false)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=50, nullable=false)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="enroll", type="integer", length=10, nullable=false)
     */
    private $enroll;

    /**
     * @var string
     *
     * @ORM\Column(name="tplcfg", type="string", length=100, nullable=false)
     */
    private $tplcfg;

    /**
     * @var string
     *
     * @ORM\Column(name="imagelist", type="text", length=0, nullable=false)
     */
    private $imagelist;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=100, nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=100, nullable=false)
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="subtime", type="date", length=100, nullable=false)
     */
    private $subtime;

    /**
     * @var string
     *
     * @ORM\Column(name="atlas", type="text", length=0, nullable=false)
     */
    private $atlas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stime", type="date", length=10, nullable=false)
     */
    private $stime;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="string", length=100, nullable=false)
     */
    private $discount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="show_time", type="date", length=100, nullable=false)
     */
    private $show_time;

    /**
     * @var string
     *
     * @ORM\Column(name="venue", type="string", length=100, nullable=false)
     */
    private $venue;

    /**
     * @var string
     *
     * @ORM\Column(name="hotline", type="string", length=100, nullable=false)
     */
    private $hotline;

    /**
     * @var string
     *
     * @ORM\Column(name="banner", type="text", length=0, nullable=false)
     */
    private $banner;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="integer", length=10, nullable=false)
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
     * Set category
     *
     * @param integer $category
     * @return Topic
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set pid
     *
     * @param integer $pid
     * @return Topic
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    
        return $this;
    }

    /**
     * Get pid
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * Set abstract
     *
     * @param string $abstract
     * @return Topic
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
     * Set keywords
     *
     * @param string $keywords
     * @return Topic
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
     * Set time
     *
     * @param \DateTime $time
     * @return Topic
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Topic
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set enroll
     *
     * @param integer $enroll
     * @return Topic
     */
    public function setEnroll($enroll)
    {
        $this->enroll = $enroll;
    
        return $this;
    }

    /**
     * Get enroll
     *
     * @return integer 
     */
    public function getEnroll()
    {
        return $this->enroll;
    }

    /**
     * Set tplcfg
     *
     * @param string $tplcfg
     * @return Topic
     */
    public function setTplcfg($tplcfg)
    {
        $this->tplcfg = $tplcfg;
    
        return $this;
    }

    /**
     * Get tplcfg
     *
     * @return string 
     */
    public function getTplcfg()
    {
        return $this->tplcfg;
    }

    /**
     * Set imagelist
     *
     * @param string $imagelist
     * @return Topic
     */
    public function setImagelist($imagelist)
    {
        $this->imagelist = $imagelist;
    
        return $this;
    }

    /**
     * Get imagelist
     *
     * @return string 
     */
    public function getImagelist()
    {
        return $this->imagelist;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Topic
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Topic
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set subtime
     *
     * @param \DateTime $subtime
     * @return Topic
     */
    public function setSubtime($subtime)
    {
        $this->subtime = $subtime;
    
        return $this;
    }

    /**
     * Get subtime
     *
     * @return \DateTime 
     */
    public function getSubtime()
    {
        return $this->subtime;
    }

    /**
     * Set atlas
     *
     * @param string $atlas
     * @return Topic
     */
    public function setAtlas($atlas)
    {
        $this->atlas = $atlas;
    
        return $this;
    }

    /**
     * Get atlas
     *
     * @return string 
     */
    public function getAtlas()
    {
        return $this->atlas;
    }

    /**
     * Set stime
     *
     * @param \DateTime $stime
     * @return Topic
     */
    public function setStime($stime)
    {
        $this->stime = $stime;
    
        return $this;
    }

    /**
     * Get stime
     *
     * @return \DateTime 
     */
    public function getStime()
    {
        return $this->stime;
    }

    /**
     * Set discount
     *
     * @param string $discount
     * @return Topic
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    
        return $this;
    }

    /**
     * Get discount
     *
     * @return string 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set show_time
     *
     * @param \DateTime $showTime
     * @return Topic
     */
    public function setShowTime($showTime)
    {
        $this->show_time = $showTime;
    
        return $this;
    }

    /**
     * Get show_time
     *
     * @return \DateTime 
     */
    public function getShowTime()
    {
        return $this->show_time;
    }

    /**
     * Set venue
     *
     * @param string $venue
     * @return Topic
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;
    
        return $this;
    }

    /**
     * Get venue
     *
     * @return string 
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set hotline
     *
     * @param string $hotline
     * @return Topic
     */
    public function setHotline($hotline)
    {
        $this->hotline = $hotline;
    
        return $this;
    }

    /**
     * Get hotline
     *
     * @return string 
     */
    public function getHotline()
    {
        return $this->hotline;
    }

    /**
     * Set banner
     *
     * @param string $banner
     * @return Topic
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
    
        return $this;
    }

    /**
     * Get banner
     *
     * @return string 
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
     * @return Topic
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
