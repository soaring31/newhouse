<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HousesArc
 *
 * @ORM\Table(name="houses_arc", indexes={@ORM\Index(name="aid", columns={"aid"}), @ORM\Index(name="models", columns={"models"}), @ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="area", columns={"area"}), @ORM\Index(name="cate_line", columns={"cate_line"}), @ORM\Index(name="cate_metro", columns={"cate_metro"}), @ORM\Index(name="cate_circle", columns={"cate_circle"})})
 * @ORM\Entity
 */
class HousesArc
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
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=100, nullable=false)
     */
    private $aid;

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
     * @var float
     *
     * @ORM\Column(name="mj", type="float", length=100, nullable=false)
     */
    private $mj;

    /**
     * @var float
     *
     * @ORM\Column(name="dj", type="float", length=100, nullable=false)
     */
    private $dj;

    /**
     * @var integer
     *
     * @ORM\Column(name="shi", type="smallint", length=2, nullable=false)
     */
    private $shi;

    /**
     * @var integer
     *
     * @ORM\Column(name="ting", type="smallint", length=2, nullable=false)
     */
    private $ting;

    /**
     * @var integer
     *
     * @ORM\Column(name="chu", type="smallint", length=2, nullable=false)
     */
    private $chu;

    /**
     * @var integer
     *
     * @ORM\Column(name="wei", type="smallint", length=2, nullable=false)
     */
    private $wei;

    /**
     * @var integer
     *
     * @ORM\Column(name="yangtai", type="smallint", length=2, nullable=false)
     */
    private $yangtai;

    /**
     * @var integer
     *
     * @ORM\Column(name="zlhx", type="smallint", length=2, nullable=false)
     */
    private $zlhx;

    /**
     * @var string
     *
     * @ORM\Column(name="tujis", type="text", length=0, nullable=false)
     */
    private $tujis;

    /**
     * @var float
     *
     * @ORM\Column(name="zj", type="float", length=100, nullable=false)
     */
    private $zj;

    /**
     * @var integer
     *
     * @ORM\Column(name="zxcd", type="smallint", length=2, nullable=false)
     */
    private $zxcd;

    /**
     * @var integer
     *
     * @ORM\Column(name="cx", type="integer", length=10, nullable=false)
     */
    private $cx;

    /**
     * @var integer
     *
     * @ORM\Column(name="louxing", type="smallint", length=2, nullable=false)
     */
    private $louxing;

    /**
     * @var string
     *
     * @ORM\Column(name="szlc", type="string", length=100, nullable=false)
     */
    private $szlc;

    /**
     * @var string
     *
     * @ORM\Column(name="zlc", type="string", length=100, nullable=false)
     */
    private $zlc;

    /**
     * @var string
     *
     * @ORM\Column(name="xingming", type="string", length=100, nullable=false)
     */
    private $xingming;

    /**
     * @var string
     *
     * @ORM\Column(name="lh", type="string", length=100, nullable=false)
     */
    private $lh;

    /**
     * @var string
     *
     * @ORM\Column(name="fh", type="string", length=100, nullable=false)
     */
    private $fh;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_album", type="integer", length=10, nullable=false)
     */
    private $cate_album;

    /**
     * @var integer
     *
     * @ORM\Column(name="sale", type="integer", length=10, nullable=false)
     */
    private $sale;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", length=10, nullable=false)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", length=10, nullable=false)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="traffic", type="integer", length=10, nullable=false)
     */
    private $traffic;

    /**
     * @var integer
     *
     * @ORM\Column(name="charms", type="integer", length=10, nullable=false)
     */
    private $charms;

    /**
     * @var integer
     *
     * @ORM\Column(name="environment", type="integer", length=10, nullable=false)
     */
    private $environment;

    /**
     * @var float
     *
     * @ORM\Column(name="overall", type="float", length=100, nullable=false)
     */
    private $overall;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit", type="integer", length=10, nullable=false)
     */
    private $unit;

    /**
     * @var integer
     *
     * @ORM\Column(name="floor", type="integer", length=10, nullable=false)
     */
    private $floor;

    /**
     * @var integer
     *
     * @ORM\Column(name="hushu", type="integer", length=10, nullable=false)
     */
    private $hushu;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_status", type="smallint", length=2, nullable=false)
     */
    private $cate_status;

    /**
     * @var string
     *
     * @ORM\Column(name="sgjd", type="string", length=100, nullable=false)
     */
    private $sgjd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="kpsj", type="date", length=100, nullable=false)
     */
    private $kpsj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="jfsj", type="date", length=100, nullable=false)
     */
    private $jfsj;

    /**
     * @var string
     *
     * @ORM\Column(name="shapan", type="string", length=100, nullable=false)
     */
    private $shapan;

    /**
     * @var string
     *
     * @ORM\Column(name="xkzh", type="string", length=100, nullable=false)
     */
    private $xkzh;

    /**
     * @var string
     *
     * @ORM\Column(name="dongtai", type="string", length=100, nullable=false)
     */
    private $dongtai;

    /**
     * @var string
     *
     * @ORM\Column(name="cate_pushs", type="string", length=100, nullable=false)
     */
    private $cate_pushs;

    /**
     * @var string
     *
     * @ORM\Column(name="form", type="string", length=100, nullable=false)
     */
    private $form;

    /**
     * @var integer
     *
     * @ORM\Column(name="fromid", type="integer", length=10, nullable=false)
     */
    private $fromid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="limit_time", type="date", length=10, nullable=false)
     */
    private $limit_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="yj", type="integer", length=10, nullable=false)
     */
    private $yj;

    /**
     * @var integer
     *
     * @ORM\Column(name="top", type="integer", length=10, nullable=false)
     */
    private $top;

    /**
     * @var integer
     *
     * @ORM\Column(name="yiyuding", type="integer", length=10, nullable=false)
     */
    private $yiyuding;

    /**
     * @var integer
     *
     * @ORM\Column(name="yituijian", type="integer", length=10, nullable=false)
     */
    private $yituijian;

    /**
     * @var string
     *
     * @ORM\Column(name="lpfx", type="string", length=100, nullable=false)
     */
    private $lpfx;

    /**
     * @var integer
     *
     * @ORM\Column(name="yongjin", type="integer", length=10, nullable=false)
     */
    private $yongjin;

    /**
     * @var string
     *
     * @ORM\Column(name="seat", type="string", length=100, nullable=false)
     */
    private $seat;

    /**
     * @var integer
     *
     * @ORM\Column(name="shichangjia", type="integer", length=100, nullable=false)
     */
    private $shichangjia;

    /**
     * @var integer
     *
     * @ORM\Column(name="yichengjiao", type="integer", length=100, nullable=false)
     */
    private $yichengjiao;

    /**
     * @var integer
     *
     * @ORM\Column(name="ss_build", type="smallint", length=2, nullable=false)
     */
    private $ss_build;

    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", length=10, nullable=false)
     */
    private $vote;

    /**
     * @var integer
     *
     * @ORM\Column(name="toid", type="integer", length=10, nullable=false)
     */
    private $toid;

    /**
     * @var string
     *
     * @ORM\Column(name="dyfl", type="string", length=100, nullable=false)
     */
    private $dyfl;

    /**
     * @var string
     *
     * @ORM\Column(name="yinxiang", type="string", length=100, nullable=false)
     */
    private $yinxiang;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=100, nullable=false)
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_type", type="integer", length=100, nullable=false)
     */
    private $cate_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked = '1';

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
     * @ORM\Column(name="region", type="integer", length=100, nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="text", length=0, nullable=false)
     */
    private $user_agent;

    /**
     * @var string
     *
     * @ORM\Column(name="lpmc", type="string", length=100, nullable=false)
     */
    private $lpmc;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_line", type="integer", length=10, nullable=false)
     */
    private $cate_line;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_metro", type="integer", length=10, nullable=false)
     */
    private $cate_metro;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_circle", type="integer", length=10, nullable=false)
     */
    private $cate_circle;

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
     * @return HousesArc
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
     * Set aid
     *
     * @param integer $aid
     * @return HousesArc
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
     * Set models
     *
     * @param string $models
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * Set ksnum
     *
     * @param integer $ksnum
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * Set mj
     *
     * @param float $mj
     * @return HousesArc
     */
    public function setMj($mj)
    {
        $this->mj = $mj;
    
        return $this;
    }

    /**
     * Get mj
     *
     * @return float 
     */
    public function getMj()
    {
        return $this->mj;
    }

    /**
     * Set dj
     *
     * @param float $dj
     * @return HousesArc
     */
    public function setDj($dj)
    {
        $this->dj = $dj;
    
        return $this;
    }

    /**
     * Get dj
     *
     * @return float 
     */
    public function getDj()
    {
        return $this->dj;
    }

    /**
     * Set shi
     *
     * @param integer $shi
     * @return HousesArc
     */
    public function setShi($shi)
    {
        $this->shi = $shi;
    
        return $this;
    }

    /**
     * Get shi
     *
     * @return integer 
     */
    public function getShi()
    {
        return $this->shi;
    }

    /**
     * Set ting
     *
     * @param integer $ting
     * @return HousesArc
     */
    public function setTing($ting)
    {
        $this->ting = $ting;
    
        return $this;
    }

    /**
     * Get ting
     *
     * @return integer 
     */
    public function getTing()
    {
        return $this->ting;
    }

    /**
     * Set chu
     *
     * @param integer $chu
     * @return HousesArc
     */
    public function setChu($chu)
    {
        $this->chu = $chu;
    
        return $this;
    }

    /**
     * Get chu
     *
     * @return integer 
     */
    public function getChu()
    {
        return $this->chu;
    }

    /**
     * Set wei
     *
     * @param integer $wei
     * @return HousesArc
     */
    public function setWei($wei)
    {
        $this->wei = $wei;
    
        return $this;
    }

    /**
     * Get wei
     *
     * @return integer 
     */
    public function getWei()
    {
        return $this->wei;
    }

    /**
     * Set yangtai
     *
     * @param integer $yangtai
     * @return HousesArc
     */
    public function setYangtai($yangtai)
    {
        $this->yangtai = $yangtai;
    
        return $this;
    }

    /**
     * Get yangtai
     *
     * @return integer 
     */
    public function getYangtai()
    {
        return $this->yangtai;
    }

    /**
     * Set zlhx
     *
     * @param integer $zlhx
     * @return HousesArc
     */
    public function setZlhx($zlhx)
    {
        $this->zlhx = $zlhx;
    
        return $this;
    }

    /**
     * Get zlhx
     *
     * @return integer 
     */
    public function getZlhx()
    {
        return $this->zlhx;
    }

    /**
     * Set tujis
     *
     * @param string $tujis
     * @return HousesArc
     */
    public function setTujis($tujis)
    {
        $this->tujis = $tujis;
    
        return $this;
    }

    /**
     * Get tujis
     *
     * @return string 
     */
    public function getTujis()
    {
        return $this->tujis;
    }

    /**
     * Set zj
     *
     * @param float $zj
     * @return HousesArc
     */
    public function setZj($zj)
    {
        $this->zj = $zj;
    
        return $this;
    }

    /**
     * Get zj
     *
     * @return float 
     */
    public function getZj()
    {
        return $this->zj;
    }

    /**
     * Set zxcd
     *
     * @param integer $zxcd
     * @return HousesArc
     */
    public function setZxcd($zxcd)
    {
        $this->zxcd = $zxcd;
    
        return $this;
    }

    /**
     * Get zxcd
     *
     * @return integer 
     */
    public function getZxcd()
    {
        return $this->zxcd;
    }

    /**
     * Set cx
     *
     * @param integer $cx
     * @return HousesArc
     */
    public function setCx($cx)
    {
        $this->cx = $cx;
    
        return $this;
    }

    /**
     * Get cx
     *
     * @return integer 
     */
    public function getCx()
    {
        return $this->cx;
    }

    /**
     * Set louxing
     *
     * @param integer $louxing
     * @return HousesArc
     */
    public function setLouxing($louxing)
    {
        $this->louxing = $louxing;
    
        return $this;
    }

    /**
     * Get louxing
     *
     * @return integer 
     */
    public function getLouxing()
    {
        return $this->louxing;
    }

    /**
     * Set szlc
     *
     * @param string $szlc
     * @return HousesArc
     */
    public function setSzlc($szlc)
    {
        $this->szlc = $szlc;
    
        return $this;
    }

    /**
     * Get szlc
     *
     * @return string 
     */
    public function getSzlc()
    {
        return $this->szlc;
    }

    /**
     * Set zlc
     *
     * @param string $zlc
     * @return HousesArc
     */
    public function setZlc($zlc)
    {
        $this->zlc = $zlc;
    
        return $this;
    }

    /**
     * Get zlc
     *
     * @return string 
     */
    public function getZlc()
    {
        return $this->zlc;
    }

    /**
     * Set xingming
     *
     * @param string $xingming
     * @return HousesArc
     */
    public function setXingming($xingming)
    {
        $this->xingming = $xingming;
    
        return $this;
    }

    /**
     * Get xingming
     *
     * @return string 
     */
    public function getXingming()
    {
        return $this->xingming;
    }

    /**
     * Set lh
     *
     * @param string $lh
     * @return HousesArc
     */
    public function setLh($lh)
    {
        $this->lh = $lh;
    
        return $this;
    }

    /**
     * Get lh
     *
     * @return string 
     */
    public function getLh()
    {
        return $this->lh;
    }

    /**
     * Set fh
     *
     * @param string $fh
     * @return HousesArc
     */
    public function setFh($fh)
    {
        $this->fh = $fh;
    
        return $this;
    }

    /**
     * Get fh
     *
     * @return string 
     */
    public function getFh()
    {
        return $this->fh;
    }

    /**
     * Set cate_album
     *
     * @param integer $cateAlbum
     * @return HousesArc
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
     * Set sale
     *
     * @param integer $sale
     * @return HousesArc
     */
    public function setSale($sale)
    {
        $this->sale = $sale;
    
        return $this;
    }

    /**
     * Get sale
     *
     * @return integer 
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return HousesArc
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return HousesArc
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set traffic
     *
     * @param integer $traffic
     * @return HousesArc
     */
    public function setTraffic($traffic)
    {
        $this->traffic = $traffic;
    
        return $this;
    }

    /**
     * Get traffic
     *
     * @return integer 
     */
    public function getTraffic()
    {
        return $this->traffic;
    }

    /**
     * Set charms
     *
     * @param integer $charms
     * @return HousesArc
     */
    public function setCharms($charms)
    {
        $this->charms = $charms;
    
        return $this;
    }

    /**
     * Get charms
     *
     * @return integer 
     */
    public function getCharms()
    {
        return $this->charms;
    }

    /**
     * Set environment
     *
     * @param integer $environment
     * @return HousesArc
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    
        return $this;
    }

    /**
     * Get environment
     *
     * @return integer 
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set overall
     *
     * @param float $overall
     * @return HousesArc
     */
    public function setOverall($overall)
    {
        $this->overall = $overall;
    
        return $this;
    }

    /**
     * Get overall
     *
     * @return float 
     */
    public function getOverall()
    {
        return $this->overall;
    }

    /**
     * Set unit
     *
     * @param integer $unit
     * @return HousesArc
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return integer 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set floor
     *
     * @param integer $floor
     * @return HousesArc
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    
        return $this;
    }

    /**
     * Get floor
     *
     * @return integer 
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set hushu
     *
     * @param integer $hushu
     * @return HousesArc
     */
    public function setHushu($hushu)
    {
        $this->hushu = $hushu;
    
        return $this;
    }

    /**
     * Get hushu
     *
     * @return integer 
     */
    public function getHushu()
    {
        return $this->hushu;
    }

    /**
     * Set cate_status
     *
     * @param integer $cateStatus
     * @return HousesArc
     */
    public function setCateStatus($cateStatus)
    {
        $this->cate_status = $cateStatus;
    
        return $this;
    }

    /**
     * Get cate_status
     *
     * @return integer 
     */
    public function getCateStatus()
    {
        return $this->cate_status;
    }

    /**
     * Set sgjd
     *
     * @param string $sgjd
     * @return HousesArc
     */
    public function setSgjd($sgjd)
    {
        $this->sgjd = $sgjd;
    
        return $this;
    }

    /**
     * Get sgjd
     *
     * @return string 
     */
    public function getSgjd()
    {
        return $this->sgjd;
    }

    /**
     * Set kpsj
     *
     * @param \DateTime $kpsj
     * @return HousesArc
     */
    public function setKpsj($kpsj)
    {
        $this->kpsj = $kpsj;
    
        return $this;
    }

    /**
     * Get kpsj
     *
     * @return \DateTime 
     */
    public function getKpsj()
    {
        return $this->kpsj;
    }

    /**
     * Set jfsj
     *
     * @param \DateTime $jfsj
     * @return HousesArc
     */
    public function setJfsj($jfsj)
    {
        $this->jfsj = $jfsj;
    
        return $this;
    }

    /**
     * Get jfsj
     *
     * @return \DateTime 
     */
    public function getJfsj()
    {
        return $this->jfsj;
    }

    /**
     * Set shapan
     *
     * @param string $shapan
     * @return HousesArc
     */
    public function setShapan($shapan)
    {
        $this->shapan = $shapan;
    
        return $this;
    }

    /**
     * Get shapan
     *
     * @return string 
     */
    public function getShapan()
    {
        return $this->shapan;
    }

    /**
     * Set xkzh
     *
     * @param string $xkzh
     * @return HousesArc
     */
    public function setXkzh($xkzh)
    {
        $this->xkzh = $xkzh;
    
        return $this;
    }

    /**
     * Get xkzh
     *
     * @return string 
     */
    public function getXkzh()
    {
        return $this->xkzh;
    }

    /**
     * Set dongtai
     *
     * @param string $dongtai
     * @return HousesArc
     */
    public function setDongtai($dongtai)
    {
        $this->dongtai = $dongtai;
    
        return $this;
    }

    /**
     * Get dongtai
     *
     * @return string 
     */
    public function getDongtai()
    {
        return $this->dongtai;
    }

    /**
     * Set cate_pushs
     *
     * @param string $catePushs
     * @return HousesArc
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
     * Set form
     *
     * @param string $form
     * @return HousesArc
     */
    public function setForm($form)
    {
        $this->form = $form;
    
        return $this;
    }

    /**
     * Get form
     *
     * @return string 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set fromid
     *
     * @param integer $fromid
     * @return HousesArc
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
     * Set limit_time
     *
     * @param \DateTime $limitTime
     * @return HousesArc
     */
    public function setLimitTime($limitTime)
    {
        $this->limit_time = $limitTime;
    
        return $this;
    }

    /**
     * Get limit_time
     *
     * @return \DateTime 
     */
    public function getLimitTime()
    {
        return $this->limit_time;
    }

    /**
     * Set yj
     *
     * @param integer $yj
     * @return HousesArc
     */
    public function setYj($yj)
    {
        $this->yj = $yj;
    
        return $this;
    }

    /**
     * Get yj
     *
     * @return integer 
     */
    public function getYj()
    {
        return $this->yj;
    }

    /**
     * Set top
     *
     * @param integer $top
     * @return HousesArc
     */
    public function setTop($top)
    {
        $this->top = $top;
    
        return $this;
    }

    /**
     * Get top
     *
     * @return integer 
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set yiyuding
     *
     * @param integer $yiyuding
     * @return HousesArc
     */
    public function setYiyuding($yiyuding)
    {
        $this->yiyuding = $yiyuding;
    
        return $this;
    }

    /**
     * Get yiyuding
     *
     * @return integer 
     */
    public function getYiyuding()
    {
        return $this->yiyuding;
    }

    /**
     * Set yituijian
     *
     * @param integer $yituijian
     * @return HousesArc
     */
    public function setYituijian($yituijian)
    {
        $this->yituijian = $yituijian;
    
        return $this;
    }

    /**
     * Get yituijian
     *
     * @return integer 
     */
    public function getYituijian()
    {
        return $this->yituijian;
    }

    /**
     * Set lpfx
     *
     * @param string $lpfx
     * @return HousesArc
     */
    public function setLpfx($lpfx)
    {
        $this->lpfx = $lpfx;
    
        return $this;
    }

    /**
     * Get lpfx
     *
     * @return string 
     */
    public function getLpfx()
    {
        return $this->lpfx;
    }

    /**
     * Set yongjin
     *
     * @param integer $yongjin
     * @return HousesArc
     */
    public function setYongjin($yongjin)
    {
        $this->yongjin = $yongjin;
    
        return $this;
    }

    /**
     * Get yongjin
     *
     * @return integer 
     */
    public function getYongjin()
    {
        return $this->yongjin;
    }

    /**
     * Set seat
     *
     * @param string $seat
     * @return HousesArc
     */
    public function setSeat($seat)
    {
        $this->seat = $seat;
    
        return $this;
    }

    /**
     * Get seat
     *
     * @return string 
     */
    public function getSeat()
    {
        return $this->seat;
    }

    /**
     * Set shichangjia
     *
     * @param integer $shichangjia
     * @return HousesArc
     */
    public function setShichangjia($shichangjia)
    {
        $this->shichangjia = $shichangjia;
    
        return $this;
    }

    /**
     * Get shichangjia
     *
     * @return integer 
     */
    public function getShichangjia()
    {
        return $this->shichangjia;
    }

    /**
     * Set yichengjiao
     *
     * @param integer $yichengjiao
     * @return HousesArc
     */
    public function setYichengjiao($yichengjiao)
    {
        $this->yichengjiao = $yichengjiao;
    
        return $this;
    }

    /**
     * Get yichengjiao
     *
     * @return integer 
     */
    public function getYichengjiao()
    {
        return $this->yichengjiao;
    }

    /**
     * Set ss_build
     *
     * @param integer $ssBuild
     * @return HousesArc
     */
    public function setSsBuild($ssBuild)
    {
        $this->ss_build = $ssBuild;
    
        return $this;
    }

    /**
     * Get ss_build
     *
     * @return integer 
     */
    public function getSsBuild()
    {
        return $this->ss_build;
    }

    /**
     * Set vote
     *
     * @param integer $vote
     * @return HousesArc
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    
        return $this;
    }

    /**
     * Get vote
     *
     * @return integer 
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set toid
     *
     * @param integer $toid
     * @return HousesArc
     */
    public function setToid($toid)
    {
        $this->toid = $toid;
    
        return $this;
    }

    /**
     * Get toid
     *
     * @return integer 
     */
    public function getToid()
    {
        return $this->toid;
    }

    /**
     * Set dyfl
     *
     * @param string $dyfl
     * @return HousesArc
     */
    public function setDyfl($dyfl)
    {
        $this->dyfl = $dyfl;
    
        return $this;
    }

    /**
     * Get dyfl
     *
     * @return string 
     */
    public function getDyfl()
    {
        return $this->dyfl;
    }

    /**
     * Set yinxiang
     *
     * @param string $yinxiang
     * @return HousesArc
     */
    public function setYinxiang($yinxiang)
    {
        $this->yinxiang = $yinxiang;
    
        return $this;
    }

    /**
     * Get yinxiang
     *
     * @return string 
     */
    public function getYinxiang()
    {
        return $this->yinxiang;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return HousesArc
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
     * Set cate_type
     *
     * @param integer $cateType
     * @return HousesArc
     */
    public function setCateType($cateType)
    {
        $this->cate_type = $cateType;
    
        return $this;
    }

    /**
     * Get cate_type
     *
     * @return integer 
     */
    public function getCateType()
    {
        return $this->cate_type;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return HousesArc
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
     * Set uid
     *
     * @param integer $uid
     * @return HousesArc
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
     * @return HousesArc
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
     * Set region
     *
     * @param integer $region
     * @return HousesArc
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return integer 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set user_agent
     *
     * @param string $userAgent
     * @return HousesArc
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
     * Set lpmc
     *
     * @param string $lpmc
     * @return HousesArc
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
     * Set cate_line
     *
     * @param integer $cateLine
     * @return HousesArc
     */
    public function setCateLine($cateLine)
    {
        $this->cate_line = $cateLine;
    
        return $this;
    }

    /**
     * Get cate_line
     *
     * @return integer 
     */
    public function getCateLine()
    {
        return $this->cate_line;
    }

    /**
     * Set cate_metro
     *
     * @param integer $cateMetro
     * @return HousesArc
     */
    public function setCateMetro($cateMetro)
    {
        $this->cate_metro = $cateMetro;
    
        return $this;
    }

    /**
     * Get cate_metro
     *
     * @return integer 
     */
    public function getCateMetro()
    {
        return $this->cate_metro;
    }

    /**
     * Set cate_circle
     *
     * @param integer $cateCircle
     * @return HousesArc
     */
    public function setCateCircle($cateCircle)
    {
        $this->cate_circle = $cateCircle;
    
        return $this;
    }

    /**
     * Get cate_circle
     *
     * @return integer 
     */
    public function getCateCircle()
    {
        return $this->cate_circle;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
     * @return HousesArc
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
