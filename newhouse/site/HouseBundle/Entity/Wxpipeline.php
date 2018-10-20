<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxpipeline
 *
 * @ORM\Table(name="wxpipeline")
 * @ORM\Entity
 */
class Wxpipeline
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
     * @ORM\Column(name="postertitle", type="string", length=50, nullable=false)
     */
    private $postertitle;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="string", length=10, nullable=false)
     */
    private $keyword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="date", length=10, nullable=false)
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="date", length=10, nullable=false)
     */
    private $enddate;

    /**
     * @var string
     *
     * @ORM\Column(name="poster_img", type="string", length=100, nullable=false)
     */
    private $poster_img;

    /**
     * @var integer
     *
     * @ORM\Column(name="reward_type", type="smallint", length=2, nullable=false)
     */
    private $reward_type = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="lv1_cash", type="integer", length=10, nullable=false)
     */
    private $lv1_cash;

    /**
     * @var integer
     *
     * @ORM\Column(name="lv2_cash", type="integer", length=10, nullable=false)
     */
    private $lv2_cash;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_cash", type="integer", length=10, nullable=false)
     */
    private $max_cash;

    /**
     * @var integer
     *
     * @ORM\Column(name="authtest", type="integer", length=10, nullable=false)
     */
    private $authtest;

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
     * Set postertitle
     *
     * @param string $postertitle
     * @return Wxpipeline
     */
    public function setPostertitle($postertitle)
    {
        $this->postertitle = $postertitle;
    
        return $this;
    }

    /**
     * Get postertitle
     *
     * @return string 
     */
    public function getPostertitle()
    {
        return $this->postertitle;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return Wxpipeline
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
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return Wxpipeline
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    
        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Wxpipeline
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
     * Set poster_img
     *
     * @param string $posterImg
     * @return Wxpipeline
     */
    public function setPosterImg($posterImg)
    {
        $this->poster_img = $posterImg;
    
        return $this;
    }

    /**
     * Get poster_img
     *
     * @return string 
     */
    public function getPosterImg()
    {
        return $this->poster_img;
    }

    /**
     * Set reward_type
     *
     * @param integer $rewardType
     * @return Wxpipeline
     */
    public function setRewardType($rewardType)
    {
        $this->reward_type = $rewardType;
    
        return $this;
    }

    /**
     * Get reward_type
     *
     * @return integer 
     */
    public function getRewardType()
    {
        return $this->reward_type;
    }

    /**
     * Set lv1_cash
     *
     * @param integer $lv1Cash
     * @return Wxpipeline
     */
    public function setLv1Cash($lv1Cash)
    {
        $this->lv1_cash = $lv1Cash;
    
        return $this;
    }

    /**
     * Get lv1_cash
     *
     * @return integer 
     */
    public function getLv1Cash()
    {
        return $this->lv1_cash;
    }

    /**
     * Set lv2_cash
     *
     * @param integer $lv2Cash
     * @return Wxpipeline
     */
    public function setLv2Cash($lv2Cash)
    {
        $this->lv2_cash = $lv2Cash;
    
        return $this;
    }

    /**
     * Get lv2_cash
     *
     * @return integer 
     */
    public function getLv2Cash()
    {
        return $this->lv2_cash;
    }

    /**
     * Set max_cash
     *
     * @param integer $maxCash
     * @return Wxpipeline
     */
    public function setMaxCash($maxCash)
    {
        $this->max_cash = $maxCash;
    
        return $this;
    }

    /**
     * Get max_cash
     *
     * @return integer 
     */
    public function getMaxCash()
    {
        return $this->max_cash;
    }

    /**
     * Set authtest
     *
     * @param integer $authtest
     * @return Wxpipeline
     */
    public function setAuthtest($authtest)
    {
        $this->authtest = $authtest;
    
        return $this;
    }

    /**
     * Get authtest
     *
     * @return integer 
     */
    public function getAuthtest()
    {
        return $this->authtest;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxpipeline
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
     * @return Wxpipeline
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
     * @return Wxpipeline
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
     * @return Wxpipeline
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
     * @return Wxpipeline
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
     * @return Wxpipeline
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
     * @return Wxpipeline
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
     * @return Wxpipeline
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
