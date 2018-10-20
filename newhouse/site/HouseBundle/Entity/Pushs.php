<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pushs
 *
 * @ORM\Table(name="pushs", indexes={@ORM\Index(name="fromid", columns={"fromid"}), @ORM\Index(name="cate_pushs", columns={"cate_pushs"}), @ORM\Index(name="models", columns={"models"}), @ORM\Index(name="start_time", columns={"start_time"}), @ORM\Index(name="end_time", columns={"end_time"}), @ORM\Index(name="sort_fixed", columns={"sort_fixed"}), @ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="area", columns={"area"})})
 * @ORM\Entity
 */
class Pushs
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
     * @ORM\Column(name="fromid", type="integer", length=10, nullable=false)
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
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort = '99';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="date", length=10, nullable=false)
     */
    private $start_time;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="date", length=10, nullable=false)
     */
    private $end_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_fixed", type="integer", length=10, nullable=false)
     */
    private $sort_fixed;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=0, nullable=false)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="flash", type="text", length=0, nullable=false)
     */
    private $flash;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ad", type="boolean", length=1, nullable=false)
     */
    private $ad;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="text", length=0, nullable=false)
     */
    private $html;

    /**
     * @var integer
     *
     * @ORM\Column(name="pid", type="integer", length=10, nullable=false)
     */
    private $pid;

    /**
     * @var string
     *
     * @ORM\Column(name="urlparams", type="text", length=0, nullable=false)
     */
    private $urlparams;

    /**
     * @var integer
     *
     * @ORM\Column(name="menuid", type="integer", length=10, nullable=false)
     */
    private $menuid;

    /**
     * @var string
     *
     * @ORM\Column(name="navicon", type="string", length=100, nullable=false)
     */
    private $navicon;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", length=0, nullable=false)
     */
    private $abstract;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=100, nullable=false)
     */
    private $uid;

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
     * @ORM\Column(name="active", type="string", length=100, nullable=false)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbsm", type="text", length=0, nullable=false)
     */
    private $thumbsm;

    /**
     * @var string
     *
     * @ORM\Column(name="style", type="string", length=10, nullable=false)
     */
    private $style;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=100, nullable=false)
     */
    private $target;

    /**
     * @var string
     *
     * @ORM\Column(name="subname", type="string", length=52, nullable=false)
     */
    private $subname;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes", type="string", length=10, nullable=false)
     */
    private $attributes;

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
     * @param integer $fromid
     * @return Pushs
     */
    public function setFromid($fromid)
    {
        $this->fromid = $fromid;

        return $this;
    }

    /**
     * Get fromid
     *
     * @return integer 
     */
    public function getFromid()
    {
        return $this->fromid;
    }

    /**
     * Set cate_pushs
     *
     * @param string $catePushs
     * @return Pushs
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
     * Set models
     *
     * @param string $models
     * @return Pushs
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
     * Set checked
     *
     * @param integer $checked
     * @return Pushs
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
     * Set sort
     *
     * @param integer $sort
     * @return Pushs
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
     * Set start_time
     *
     * @param \DateTime $startTime
     * @return Pushs
     */
    public function setStartTime($startTime)
    {
        $this->start_time = $startTime;

        return $this;
    }

    /**
     * Get start_time
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Set end_time
     *
     * @param \DateTime $endTime
     * @return Pushs
     */
    public function setEndTime($endTime)
    {
        $this->end_time = $endTime;

        return $this;
    }

    /**
     * Get end_time
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Set sort_fixed
     *
     * @param integer $sortFixed
     * @return Pushs
     */
    public function setSortFixed($sortFixed)
    {
        $this->sort_fixed = $sortFixed;

        return $this;
    }

    /**
     * Get sort_fixed
     *
     * @return integer 
     */
    public function getSortFixed()
    {
        return $this->sort_fixed;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Pushs
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
     * Set url
     *
     * @param string $url
     * @return Pushs
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return Pushs
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
     * Set flash
     *
     * @param string $flash
     * @return Pushs
     */
    public function setFlash($flash)
    {
        $this->flash = $flash;

        return $this;
    }

    /**
     * Get flash
     *
     * @return string 
     */
    public function getFlash()
    {
        return $this->flash;
    }

    /**
     * Set ad
     *
     * @param boolean $ad
     * @return Pushs
     */
    public function setAd($ad)
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return boolean 
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * Set html
     *
     * @param string $html
     * @return Pushs
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get html
     *
     * @return string 
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set pid
     *
     * @param integer $pid
     * @return Pushs
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
     * Set urlparams
     *
     * @param string $urlparams
     * @return Pushs
     */
    public function setUrlparams($urlparams)
    {
        $this->urlparams = $urlparams;

        return $this;
    }

    /**
     * Get urlparams
     *
     * @return string 
     */
    public function getUrlparams()
    {
        return $this->urlparams;
    }

    /**
     * Set menuid
     *
     * @param integer $menuid
     * @return Pushs
     */
    public function setMenuid($menuid)
    {
        $this->menuid = $menuid;

        return $this;
    }

    /**
     * Get menuid
     *
     * @return integer 
     */
    public function getMenuid()
    {
        return $this->menuid;
    }

    /**
     * Set navicon
     *
     * @param string $navicon
     * @return Pushs
     */
    public function setNavicon($navicon)
    {
        $this->navicon = $navicon;

        return $this;
    }

    /**
     * Get navicon
     *
     * @return string 
     */
    public function getNavicon()
    {
        return $this->navicon;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return Pushs
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
     * Set uid
     *
     * @param integer $uid
     * @return Pushs
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
     * Set area
     *
     * @param integer $area
     * @return Pushs
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
     * @return Pushs
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
     * Set active
     *
     * @param string $active
     * @return Pushs
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set thumbsm
     *
     * @param string $thumbsm
     * @return Pushs
     */
    public function setThumbsm($thumbsm)
    {
        $this->thumbsm = $thumbsm;

        return $this;
    }

    /**
     * Get thumbsm
     *
     * @return string 
     */
    public function getThumbsm()
    {
        return $this->thumbsm;
    }

    /**
     * Set style
     *
     * @param string $style
     * @return Pushs
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string 
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set target
     *
     * @param string $target
     * @return Pushs
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return string 
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set subname
     *
     * @param string $subname
     * @return Pushs
     */
    public function setSubname($subname)
    {
        $this->subname = $subname;

        return $this;
    }

    /**
     * Get subname
     *
     * @return string 
     */
    public function getSubname()
    {
        return $this->subname;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Pushs
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
     * Set issystem
     *
     * @param boolean $issystem
     * @return Pushs
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
     * @return Pushs
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
     * @return Pushs
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
     * @return Pushs
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
     * @return Pushs
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
