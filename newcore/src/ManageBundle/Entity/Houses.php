<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Houses
 *
 * @ORM\Table(name="houses", indexes={@ORM\Index(name="cate_type", columns={"cate_type"}), @ORM\Index(name="area", columns={"area"}), @ORM\Index(name="kpdate", columns={"kpdate"}), @ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="region", columns={"region"})})
 * @ORM\Entity
 */
class Houses
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
     * @ORM\Column(name="cate_type", type="string", length=100, nullable=false)
     */
    private $cate_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=10, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_status", type="integer", length=10, nullable=false)
     */
    private $cate_status;

    /**
     * @var string
     *
     * @ORM\Column(name="loupanlogo", type="text", length=0, nullable=false)
     */
    private $loupanlogo;

    /**
     * @var string
     *
     * @ORM\Column(name="lphf", type="text", length=0, nullable=false)
     */
    private $lphf;

    /**
     * @var float
     *
     * @ORM\Column(name="dj", type="float", length=100, nullable=false)
     */
    private $dj;

    /**
     * @var float
     *
     * @ORM\Column(name="jgjj", type="float", length=100, nullable=false)
     */
    private $jgjj;

    /**
     * @var float
     *
     * @ORM\Column(name="jdjj", type="float", length=100, nullable=false)
     */
    private $jdjj;

    /**
     * @var string
     *
     * @ORM\Column(name="bdsm", type="string", length=100, nullable=false)
     */
    private $bdsm;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=100, nullable=false)
     */
    private $map;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="wygs", type="string", length=100, nullable=false)
     */
    private $wygs;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="wydz", type="string", length=100, nullable=false)
     */
    private $wydz;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="kpdate", type="date", length=10, nullable=false)
     */
    private $kpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="kpsm", type="string", length=100, nullable=false)
     */
    private $kpsm;

    /**
     * @var integer
     *
     * @ORM\Column(name="zxcd", type="integer", length=10, nullable=false)
     */
    private $zxcd;

    /**
     * @var string
     *
     * @ORM\Column(name="lcs", type="string", length=100, nullable=false)
     */
    private $lcs;

    /**
     * @var integer
     *
     * @ORM\Column(name="hxs", type="integer", length=10, nullable=false)
     */
    private $hxs;

    /**
     * @var string
     *
     * @ORM\Column(name="tslp", type="string", length=100, nullable=false)
     */
    private $tslp;

    /**
     * @var integer
     *
     * @ORM\Column(name="top", type="integer", length=10, nullable=false)
     */
    private $top;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", length=2, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked;

    /**
     * @var string
     *
     * @ORM\Column(name="house_developer", type="string", length=100, nullable=false)
     */
    private $house_developer;

    /**
     * @var string
     *
     * @ORM\Column(name="wyf", type="string", length=100, nullable=false)
     */
    private $wyf;

    /**
     * @var string
     *
     * @ORM\Column(name="xkzh", type="string", length=100, nullable=false)
     */
    private $xkzh;

    /**
     * @var string
     *
     * @ORM\Column(name="sldz", type="string", length=100, nullable=false)
     */
    private $sldz;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="srcid_", type="integer", length=11, nullable=false)
     */
    private $srcid_;

    /**
     * @var string
     *
     * @ORM\Column(name="ename", type="string", length=100, nullable=false)
     */
    private $ename;

    /**
     * @var string
     *
     * @ORM\Column(name="geohash", type="string", length=20, nullable=false)
     */
    private $geohash;

    /**
     * @var string
     *
     * @ORM\Column(name="jfrq", type="string", length=100, nullable=false)
     */
    private $jfrq;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="dzsp", type="text", length=0, nullable=false)
     */
    private $dzsp;

    /**
     * @var integer
     *
     * @ORM\Column(name="sales", type="integer", length=10, nullable=false)
     */
    private $sales;

    /**
     * @var integer
     *
     * @ORM\Column(name="rents", type="integer", length=10, nullable=false)
     */
    private $rents;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=100, nullable=false)
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="comnum", type="integer", length=10, nullable=false)
     */
    private $comnum;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_metro", type="integer", length=10, nullable=false)
     */
    private $cate_metro;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_line", type="integer", length=10, nullable=false)
     */
    private $cate_line;

    /**
     * @var integer
     *
     * @ORM\Column(name="cate_circle", type="integer", length=10, nullable=false)
     */
    private $cate_circle;

    /**
     * @var float
     *
     * @ORM\Column(name="sale_jgjj", type="float", length=100, nullable=false)
     */
    private $sale_jgjj;

    /**
     * @var float
     *
     * @ORM\Column(name="rent_jgjj", type="float", length=100, nullable=false)
     */
    private $rent_jgjj;

    /**
     * @var float
     *
     * @ORM\Column(name="sale_jdjj", type="float", length=100, nullable=false)
     */
    private $sale_jdjj;

    /**
     * @var float
     *
     * @ORM\Column(name="rent_jdjj", type="float", length=100, nullable=false)
     */
    private $rent_jdjj;

    /**
     * @var integer
     *
     * @ORM\Column(name="officetype", type="integer", length=10, nullable=false)
     */
    private $officetype;

    /**
     * @var integer
     *
     * @ORM\Column(name="shoptype", type="integer", length=10, nullable=false)
     */
    private $shoptype;

    /**
     * @var string
     *
     * @ORM\Column(name="polyline", type="text", length=0, nullable=false)
     */
    private $polyline;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", length=100, nullable=false)
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", length=100, nullable=false)
     */
    private $lat;

    /**
     * @var integer
     *
     * @ORM\Column(name="rednum", type="integer", length=10, nullable=false)
     */
    private $rednum;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", length=10, nullable=false)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="officelevel", type="integer", length=100, nullable=false)
     */
    private $officelevel;

    /**
     * @var string
     *
     * @ORM\Column(name="shopindustry", type="string", length=100, nullable=false)
     */
    private $shopindustry;

    /**
     * @var integer
     *
     * @ORM\Column(name="doornum", type="integer", length=10, nullable=false)
     */
    private $doornum;

    /**
     * @var integer
     *
     * @ORM\Column(name="albumnum", type="integer", length=10, nullable=false)
     */
    private $albumnum;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=100, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=0, nullable=false)
     */
    private $abstract;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

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
     * Set name
     *
     * @param string $name
     * @return Houses
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
     * @return Houses
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
     * Set cate_type
     *
     * @param string $cateType
     * @return Houses
     */
    public function setCateType($cateType)
    {
        $this->cate_type = $cateType;
    
        return $this;
    }

    /**
     * Get cate_type
     *
     * @return string 
     */
    public function getCateType()
    {
        return $this->cate_type;
    }

    /**
     * Set area
     *
     * @param integer $area
     * @return Houses
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
     * Set cate_status
     *
     * @param integer $cateStatus
     * @return Houses
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
     * Set loupanlogo
     *
     * @param string $loupanlogo
     * @return Houses
     */
    public function setLoupanlogo($loupanlogo)
    {
        $this->loupanlogo = $loupanlogo;
    
        return $this;
    }

    /**
     * Get loupanlogo
     *
     * @return string 
     */
    public function getLoupanlogo()
    {
        return $this->loupanlogo;
    }

    /**
     * Set lphf
     *
     * @param string $lphf
     * @return Houses
     */
    public function setLphf($lphf)
    {
        $this->lphf = $lphf;
    
        return $this;
    }

    /**
     * Get lphf
     *
     * @return string 
     */
    public function getLphf()
    {
        return $this->lphf;
    }

    /**
     * Set dj
     *
     * @param float $dj
     * @return Houses
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
     * Set jgjj
     *
     * @param float $jgjj
     * @return Houses
     */
    public function setJgjj($jgjj)
    {
        $this->jgjj = $jgjj;
    
        return $this;
    }

    /**
     * Get jgjj
     *
     * @return float 
     */
    public function getJgjj()
    {
        return $this->jgjj;
    }

    /**
     * Set jdjj
     *
     * @param float $jdjj
     * @return Houses
     */
    public function setJdjj($jdjj)
    {
        $this->jdjj = $jdjj;
    
        return $this;
    }

    /**
     * Get jdjj
     *
     * @return float 
     */
    public function getJdjj()
    {
        return $this->jdjj;
    }

    /**
     * Set bdsm
     *
     * @param string $bdsm
     * @return Houses
     */
    public function setBdsm($bdsm)
    {
        $this->bdsm = $bdsm;
    
        return $this;
    }

    /**
     * Get bdsm
     *
     * @return string 
     */
    public function getBdsm()
    {
        return $this->bdsm;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Houses
     */
    public function setMap($map)
    {
        $this->map = $map;
    
        return $this;
    }

    /**
     * Get map
     *
     * @return string 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Houses
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
     * Set wygs
     *
     * @param string $wygs
     * @return Houses
     */
    public function setWygs($wygs)
    {
        $this->wygs = $wygs;
    
        return $this;
    }

    /**
     * Get wygs
     *
     * @return string 
     */
    public function getWygs()
    {
        return $this->wygs;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Houses
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
     * Set wydz
     *
     * @param string $wydz
     * @return Houses
     */
    public function setWydz($wydz)
    {
        $this->wydz = $wydz;
    
        return $this;
    }

    /**
     * Get wydz
     *
     * @return string 
     */
    public function getWydz()
    {
        return $this->wydz;
    }

    /**
     * Set kpdate
     *
     * @param \DateTime $kpdate
     * @return Houses
     */
    public function setKpdate($kpdate)
    {
        $this->kpdate = $kpdate;
    
        return $this;
    }

    /**
     * Get kpdate
     *
     * @return \DateTime 
     */
    public function getKpdate()
    {
        return $this->kpdate;
    }

    /**
     * Set kpsm
     *
     * @param string $kpsm
     * @return Houses
     */
    public function setKpsm($kpsm)
    {
        $this->kpsm = $kpsm;
    
        return $this;
    }

    /**
     * Get kpsm
     *
     * @return string 
     */
    public function getKpsm()
    {
        return $this->kpsm;
    }

    /**
     * Set zxcd
     *
     * @param integer $zxcd
     * @return Houses
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
     * Set lcs
     *
     * @param string $lcs
     * @return Houses
     */
    public function setLcs($lcs)
    {
        $this->lcs = $lcs;
    
        return $this;
    }

    /**
     * Get lcs
     *
     * @return string 
     */
    public function getLcs()
    {
        return $this->lcs;
    }

    /**
     * Set hxs
     *
     * @param integer $hxs
     * @return Houses
     */
    public function setHxs($hxs)
    {
        $this->hxs = $hxs;
    
        return $this;
    }

    /**
     * Get hxs
     *
     * @return integer 
     */
    public function getHxs()
    {
        return $this->hxs;
    }

    /**
     * Set tslp
     *
     * @param string $tslp
     * @return Houses
     */
    public function setTslp($tslp)
    {
        $this->tslp = $tslp;
    
        return $this;
    }

    /**
     * Get tslp
     *
     * @return string 
     */
    public function getTslp()
    {
        return $this->tslp;
    }

    /**
     * Set top
     *
     * @param integer $top
     * @return Houses
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
     * Set type
     *
     * @param integer $type
     * @return Houses
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Houses
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
     * Set house_developer
     *
     * @param string $houseDeveloper
     * @return Houses
     */
    public function setHouseDeveloper($houseDeveloper)
    {
        $this->house_developer = $houseDeveloper;
    
        return $this;
    }

    /**
     * Get house_developer
     *
     * @return string 
     */
    public function getHouseDeveloper()
    {
        return $this->house_developer;
    }

    /**
     * Set wyf
     *
     * @param string $wyf
     * @return Houses
     */
    public function setWyf($wyf)
    {
        $this->wyf = $wyf;
    
        return $this;
    }

    /**
     * Get wyf
     *
     * @return string 
     */
    public function getWyf()
    {
        return $this->wyf;
    }

    /**
     * Set xkzh
     *
     * @param string $xkzh
     * @return Houses
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
     * Set sldz
     *
     * @param string $sldz
     * @return Houses
     */
    public function setSldz($sldz)
    {
        $this->sldz = $sldz;
    
        return $this;
    }

    /**
     * Get sldz
     *
     * @return string 
     */
    public function getSldz()
    {
        return $this->sldz;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Houses
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
     * Set srcid_
     *
     * @param integer $srcid
     * @return Houses
     */
    public function setSrcid($srcid)
    {
        $this->srcid_ = $srcid;
    
        return $this;
    }

    /**
     * Get srcid_
     *
     * @return integer 
     */
    public function getSrcid()
    {
        return $this->srcid_;
    }

    /**
     * Set ename
     *
     * @param string $ename
     * @return Houses
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
     * Set geohash
     *
     * @param string $geohash
     * @return Houses
     */
    public function setGeohash($geohash)
    {
        $this->geohash = $geohash;
    
        return $this;
    }

    /**
     * Get geohash
     *
     * @return string 
     */
    public function getGeohash()
    {
        return $this->geohash;
    }

    /**
     * Set jfrq
     *
     * @param string $jfrq
     * @return Houses
     */
    public function setJfrq($jfrq)
    {
        $this->jfrq = $jfrq;
    
        return $this;
    }

    /**
     * Get jfrq
     *
     * @return string 
     */
    public function getJfrq()
    {
        return $this->jfrq;
    }

    /**
     * Set models
     *
     * @param string $models
     * @return Houses
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
     * Set uid
     *
     * @param integer $uid
     * @return Houses
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
     * Set dzsp
     *
     * @param string $dzsp
     * @return Houses
     */
    public function setDzsp($dzsp)
    {
        $this->dzsp = $dzsp;
    
        return $this;
    }

    /**
     * Get dzsp
     *
     * @return string 
     */
    public function getDzsp()
    {
        return $this->dzsp;
    }

    /**
     * Set sales
     *
     * @param integer $sales
     * @return Houses
     */
    public function setSales($sales)
    {
        $this->sales = $sales;
    
        return $this;
    }

    /**
     * Get sales
     *
     * @return integer 
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set rents
     *
     * @param integer $rents
     * @return Houses
     */
    public function setRents($rents)
    {
        $this->rents = $rents;
    
        return $this;
    }

    /**
     * Get rents
     *
     * @return integer 
     */
    public function getRents()
    {
        return $this->rents;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Houses
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
     * Set comnum
     *
     * @param integer $comnum
     * @return Houses
     */
    public function setComnum($comnum)
    {
        $this->comnum = $comnum;
    
        return $this;
    }

    /**
     * Get comnum
     *
     * @return integer 
     */
    public function getComnum()
    {
        return $this->comnum;
    }

    /**
     * Set cate_metro
     *
     * @param integer $cateMetro
     * @return Houses
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
     * Set cate_line
     *
     * @param integer $cateLine
     * @return Houses
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
     * Set cate_circle
     *
     * @param integer $cateCircle
     * @return Houses
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
     * Set sale_jgjj
     *
     * @param float $saleJgjj
     * @return Houses
     */
    public function setSaleJgjj($saleJgjj)
    {
        $this->sale_jgjj = $saleJgjj;
    
        return $this;
    }

    /**
     * Get sale_jgjj
     *
     * @return float 
     */
    public function getSaleJgjj()
    {
        return $this->sale_jgjj;
    }

    /**
     * Set rent_jgjj
     *
     * @param float $rentJgjj
     * @return Houses
     */
    public function setRentJgjj($rentJgjj)
    {
        $this->rent_jgjj = $rentJgjj;
    
        return $this;
    }

    /**
     * Get rent_jgjj
     *
     * @return float 
     */
    public function getRentJgjj()
    {
        return $this->rent_jgjj;
    }

    /**
     * Set sale_jdjj
     *
     * @param float $saleJdjj
     * @return Houses
     */
    public function setSaleJdjj($saleJdjj)
    {
        $this->sale_jdjj = $saleJdjj;
    
        return $this;
    }

    /**
     * Get sale_jdjj
     *
     * @return float 
     */
    public function getSaleJdjj()
    {
        return $this->sale_jdjj;
    }

    /**
     * Set rent_jdjj
     *
     * @param float $rentJdjj
     * @return Houses
     */
    public function setRentJdjj($rentJdjj)
    {
        $this->rent_jdjj = $rentJdjj;
    
        return $this;
    }

    /**
     * Get rent_jdjj
     *
     * @return float 
     */
    public function getRentJdjj()
    {
        return $this->rent_jdjj;
    }

    /**
     * Set officetype
     *
     * @param integer $officetype
     * @return Houses
     */
    public function setOfficetype($officetype)
    {
        $this->officetype = $officetype;
    
        return $this;
    }

    /**
     * Get officetype
     *
     * @return integer 
     */
    public function getOfficetype()
    {
        return $this->officetype;
    }

    /**
     * Set shoptype
     *
     * @param integer $shoptype
     * @return Houses
     */
    public function setShoptype($shoptype)
    {
        $this->shoptype = $shoptype;
    
        return $this;
    }

    /**
     * Get shoptype
     *
     * @return integer 
     */
    public function getShoptype()
    {
        return $this->shoptype;
    }

    /**
     * Set polyline
     *
     * @param string $polyline
     * @return Houses
     */
    public function setPolyline($polyline)
    {
        $this->polyline = $polyline;
    
        return $this;
    }

    /**
     * Get polyline
     *
     * @return string 
     */
    public function getPolyline()
    {
        return $this->polyline;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Houses
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Houses
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set rednum
     *
     * @param integer $rednum
     * @return Houses
     */
    public function setRednum($rednum)
    {
        $this->rednum = $rednum;
    
        return $this;
    }

    /**
     * Get rednum
     *
     * @return integer 
     */
    public function getRednum()
    {
        return $this->rednum;
    }

    /**
     * Set region
     *
     * @param integer $region
     * @return Houses
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
     * Set officelevel
     *
     * @param integer $officelevel
     * @return Houses
     */
    public function setOfficelevel($officelevel)
    {
        $this->officelevel = $officelevel;
    
        return $this;
    }

    /**
     * Get officelevel
     *
     * @return integer 
     */
    public function getOfficelevel()
    {
        return $this->officelevel;
    }

    /**
     * Set shopindustry
     *
     * @param string $shopindustry
     * @return Houses
     */
    public function setShopindustry($shopindustry)
    {
        $this->shopindustry = $shopindustry;
    
        return $this;
    }

    /**
     * Get shopindustry
     *
     * @return string 
     */
    public function getShopindustry()
    {
        return $this->shopindustry;
    }

    /**
     * Set doornum
     *
     * @param integer $doornum
     * @return Houses
     */
    public function setDoornum($doornum)
    {
        $this->doornum = $doornum;
    
        return $this;
    }

    /**
     * Get doornum
     *
     * @return integer 
     */
    public function getDoornum()
    {
        return $this->doornum;
    }

    /**
     * Set albumnum
     *
     * @param integer $albumnum
     * @return Houses
     */
    public function setAlbumnum($albumnum)
    {
        $this->albumnum = $albumnum;
    
        return $this;
    }

    /**
     * Get albumnum
     *
     * @return integer 
     */
    public function getAlbumnum()
    {
        return $this->albumnum;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Houses
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
     * @return Houses
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
     * Set sort
     *
     * @param integer $sort
     * @return Houses
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
     * Set attributes
     *
     * @param string $attributes
     * @return Houses
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
     * @return Houses
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
     * @return Houses
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
     * @return Houses
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
     * @return Houses
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
     * @return Houses
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
