<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eventcoupon
 *
 * @ORM\Table(name="eventcoupon")
 * @ORM\Entity
 */
class Eventcoupon
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
     * @ORM\Column(name="keyword", type="string", length=100, nullable=false)
     */
    private $keyword;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=100, nullable=false)
     */
    private $abstract;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="statdate", type="date", length=10, nullable=false)
     */
    private $statdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="date", length=10, nullable=false)
     */
    private $enddate;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=255, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="endtitle", type="string", length=100, nullable=false)
     */
    private $endtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="endinfo", type="text", length=0, nullable=false)
     */
    private $endinfo;

    /**
     * @var integer
     *
     * @ORM\Column(name="setcardid", type="smallint", length=2, nullable=false)
     */
    private $setcardid = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="set1title", type="string", length=100, nullable=false)
     */
    private $set1title;

    /**
     * @var integer
     *
     * @ORM\Column(name="set1num", type="integer", length=10, nullable=false)
     */
    private $set1num;

    /**
     * @var string
     *
     * @ORM\Column(name="set2title", type="string", length=100, nullable=false)
     */
    private $set2title;

    /**
     * @var integer
     *
     * @ORM\Column(name="set2num", type="integer", length=10, nullable=false)
     */
    private $set2num;

    /**
     * @var string
     *
     * @ORM\Column(name="set3title", type="string", length=100, nullable=false)
     */
    private $set3title;

    /**
     * @var integer
     *
     * @ORM\Column(name="set3num", type="integer", length=10, nullable=false)
     */
    private $set3num;

    /**
     * @var integer
     *
     * @ORM\Column(name="allnum", type="integer", length=10, nullable=false)
     */
    private $allnum;

    /**
     * @var integer
     *
     * @ORM\Column(name="cannum", type="integer", length=10, nullable=false)
     */
    private $cannum;

    /**
     * @var string
     *
     * @ORM\Column(name="cashcode", type="string", length=15, nullable=false)
     */
    private $cashcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="mustreg", type="smallint", length=2, nullable=false)
     */
    private $mustreg;

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
     * Set keyword
     *
     * @param string $keyword
     * @return Eventcoupon
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Eventcoupon
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return Eventcoupon
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
     * Set statdate
     *
     * @param \DateTime $statdate
     * @return Eventcoupon
     */
    public function setStatdate($statdate)
    {
        $this->statdate = $statdate;
    
        return $this;
    }

    /**
     * Get statdate
     *
     * @return \DateTime 
     */
    public function getStatdate()
    {
        return $this->statdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Eventcoupon
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
     * Set content
     *
     * @param string $content
     * @return Eventcoupon
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
     * Set endtitle
     *
     * @param string $endtitle
     * @return Eventcoupon
     */
    public function setEndtitle($endtitle)
    {
        $this->endtitle = $endtitle;
    
        return $this;
    }

    /**
     * Get endtitle
     *
     * @return string 
     */
    public function getEndtitle()
    {
        return $this->endtitle;
    }

    /**
     * Set endinfo
     *
     * @param string $endinfo
     * @return Eventcoupon
     */
    public function setEndinfo($endinfo)
    {
        $this->endinfo = $endinfo;
    
        return $this;
    }

    /**
     * Get endinfo
     *
     * @return string 
     */
    public function getEndinfo()
    {
        return $this->endinfo;
    }

    /**
     * Set setcardid
     *
     * @param integer $setcardid
     * @return Eventcoupon
     */
    public function setSetcardid($setcardid)
    {
        $this->setcardid = $setcardid;
    
        return $this;
    }

    /**
     * Get setcardid
     *
     * @return integer 
     */
    public function getSetcardid()
    {
        return $this->setcardid;
    }

    /**
     * Set set1title
     *
     * @param string $set1title
     * @return Eventcoupon
     */
    public function setSet1title($set1title)
    {
        $this->set1title = $set1title;
    
        return $this;
    }

    /**
     * Get set1title
     *
     * @return string 
     */
    public function getSet1title()
    {
        return $this->set1title;
    }

    /**
     * Set set1num
     *
     * @param integer $set1num
     * @return Eventcoupon
     */
    public function setSet1num($set1num)
    {
        $this->set1num = $set1num;
    
        return $this;
    }

    /**
     * Get set1num
     *
     * @return integer 
     */
    public function getSet1num()
    {
        return $this->set1num;
    }

    /**
     * Set set2title
     *
     * @param string $set2title
     * @return Eventcoupon
     */
    public function setSet2title($set2title)
    {
        $this->set2title = $set2title;
    
        return $this;
    }

    /**
     * Get set2title
     *
     * @return string 
     */
    public function getSet2title()
    {
        return $this->set2title;
    }

    /**
     * Set set2num
     *
     * @param integer $set2num
     * @return Eventcoupon
     */
    public function setSet2num($set2num)
    {
        $this->set2num = $set2num;
    
        return $this;
    }

    /**
     * Get set2num
     *
     * @return integer 
     */
    public function getSet2num()
    {
        return $this->set2num;
    }

    /**
     * Set set3title
     *
     * @param string $set3title
     * @return Eventcoupon
     */
    public function setSet3title($set3title)
    {
        $this->set3title = $set3title;
    
        return $this;
    }

    /**
     * Get set3title
     *
     * @return string 
     */
    public function getSet3title()
    {
        return $this->set3title;
    }

    /**
     * Set set3num
     *
     * @param integer $set3num
     * @return Eventcoupon
     */
    public function setSet3num($set3num)
    {
        $this->set3num = $set3num;
    
        return $this;
    }

    /**
     * Get set3num
     *
     * @return integer 
     */
    public function getSet3num()
    {
        return $this->set3num;
    }

    /**
     * Set allnum
     *
     * @param integer $allnum
     * @return Eventcoupon
     */
    public function setAllnum($allnum)
    {
        $this->allnum = $allnum;
    
        return $this;
    }

    /**
     * Get allnum
     *
     * @return integer 
     */
    public function getAllnum()
    {
        return $this->allnum;
    }

    /**
     * Set cannum
     *
     * @param integer $cannum
     * @return Eventcoupon
     */
    public function setCannum($cannum)
    {
        $this->cannum = $cannum;
    
        return $this;
    }

    /**
     * Get cannum
     *
     * @return integer 
     */
    public function getCannum()
    {
        return $this->cannum;
    }

    /**
     * Set cashcode
     *
     * @param string $cashcode
     * @return Eventcoupon
     */
    public function setCashcode($cashcode)
    {
        $this->cashcode = $cashcode;
    
        return $this;
    }

    /**
     * Get cashcode
     *
     * @return string 
     */
    public function getCashcode()
    {
        return $this->cashcode;
    }

    /**
     * Set mustreg
     *
     * @param integer $mustreg
     * @return Eventcoupon
     */
    public function setMustreg($mustreg)
    {
        $this->mustreg = $mustreg;
    
        return $this;
    }

    /**
     * Get mustreg
     *
     * @return integer 
     */
    public function getMustreg()
    {
        return $this->mustreg;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Eventcoupon
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
     * @return Eventcoupon
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
     * @return Eventcoupon
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
     * @return Eventcoupon
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
     * @return Eventcoupon
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
     * @return Eventcoupon
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
     * @return Eventcoupon
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
     * @return Eventcoupon
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
