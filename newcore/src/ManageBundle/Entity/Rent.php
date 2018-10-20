<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rent
 *
 * @ORM\Table(name="rent")
 * @ORM\Entity
 */
class Rent
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
     * @ORM\Column(name="lpmc", type="string", length=100, nullable=false)
     */
    private $lpmc;

    /**
     * @var integer
     *
     * @ORM\Column(name="louxing", type="integer", length=10, nullable=false)
     */
    private $louxing;

    /**
     * @var string
     *
     * @ORM\Column(name="fdname", type="string", length=100, nullable=false)
     */
    private $fdname;

    /**
     * @var string
     *
     * @ORM\Column(name="fdtel", type="string", length=100, nullable=false)
     */
    private $fdtel;

    /**
     * @var string
     *
     * @ORM\Column(name="fdnote", type="text", length=0, nullable=false)
     */
    private $fdnote;

    /**
     * @var float
     *
     * @ORM\Column(name="czj", type="float", length=100, nullable=false)
     */
    private $czj;

    /**
     * @var float
     *
     * @ORM\Column(name="mj", type="float", length=100, nullable=false)
     */
    private $mj;

    /**
     * @var integer
     *
     * @ORM\Column(name="fl", type="integer", length=10, nullable=false)
     */
    private $fl;

    /**
     * @var integer
     *
     * @ORM\Column(name="shi", type="integer", length=10, nullable=false)
     */
    private $shi;

    /**
     * @var integer
     *
     * @ORM\Column(name="ting", type="integer", length=10, nullable=false)
     */
    private $ting;

    /**
     * @var integer
     *
     * @ORM\Column(name="chu", type="integer", length=10, nullable=false)
     */
    private $chu;

    /**
     * @var integer
     *
     * @ORM\Column(name="wei", type="integer", length=10, nullable=false)
     */
    private $wei;

    /**
     * @var integer
     *
     * @ORM\Column(name="yangtai", type="integer", length=10, nullable=false)
     */
    private $yangtai;

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
     * @var integer
     *
     * @ORM\Column(name="zxcd", type="integer", length=10, nullable=false)
     */
    private $zxcd;

    /**
     * @var integer
     *
     * @ORM\Column(name="cx", type="integer", length=10, nullable=false)
     */
    private $cx;

    /**
     * @var string
     *
     * @ORM\Column(name="fwpt", type="string", length=40, nullable=false)
     */
    private $fwpt;

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
     * @var string
     *
     * @ORM\Column(name="xingming", type="string", length=100, nullable=false)
     */
    private $xingming;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="fkfs", type="integer", length=10, nullable=false)
     */
    private $fkfs;

    /**
     * @var integer
     *
     * @ORM\Column(name="zlfs", type="integer", length=10, nullable=false)
     */
    private $zlfs;

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
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=10, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="czlx", type="integer", length=10, nullable=false)
     */
    private $czlx;

    /**
     * @var integer
     *
     * @ORM\Column(name="top", type="smallint", length=2, nullable=false)
     */
    private $top;

    /**
     * @var integer
     *
     * @ORM\Column(name="jian", type="smallint", length=2, nullable=false)
     */
    private $jian;

    /**
     * @var integer
     *
     * @ORM\Column(name="valid", type="integer", length=10, nullable=false)
     */
    private $valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="office_lx", type="smallint", length=2, nullable=false)
     */
    private $office_lx;

    /**
     * @var integer
     *
     * @ORM\Column(name="office_jb", type="smallint", length=2, nullable=false)
     */
    private $office_jb;

    /**
     * @var string
     *
     * @ORM\Column(name="office_ts", type="string", length=100, nullable=false)
     */
    private $office_ts;

    /**
     * @var integer
     *
     * @ORM\Column(name="shops_lx", type="smallint", length=2, nullable=false)
     */
    private $shops_lx;

    /**
     * @var string
     *
     * @ORM\Column(name="shops_hy", type="string", length=100, nullable=false)
     */
    private $shops_hy;

    /**
     * @var integer
     *
     * @ORM\Column(name="shops_type", type="smallint", length=2, nullable=false)
     */
    private $shops_type;

    /**
     * @var string
     *
     * @ORM\Column(name="shops_ts", type="string", length=100, nullable=false)
     */
    private $shops_ts;

    /**
     * @var integer
     *
     * @ORM\Column(name="zjdate", type="integer", length=10, nullable=false)
     */
    private $zjdate;

    /**
     * @var string
     *
     * @ORM\Column(name="tujis", type="text", length=0, nullable=false)
     */
    private $tujis;

    /**
     * @var integer
     *
     * @ORM\Column(name="srcid_", type="integer", length=10, nullable=false)
     */
    private $srcid_;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="cid", type="integer", length=10, nullable=false)
     */
    private $cid;

    /**
     * @var integer
     *
     * @ORM\Column(name="job", type="smallint", length=2, nullable=false)
     */
    private $job;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=100, nullable=false)
     */
    private $map;

    /**
     * @var integer
     *
     * @ORM\Column(name="fid", type="integer", length=10, nullable=false)
     */
    private $fid;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="integer", length=10, nullable=false)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="aid", type="integer", length=10, nullable=false)
     */
    private $aid;

    /**
     * @var integer
     *
     * @ORM\Column(name="usertype", type="smallint", length=2, nullable=false)
     */
    private $usertype;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=100, nullable=false)
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="enddate", type="integer", length=10, nullable=false)
     */
    private $enddate;

    /**
     * @var integer
     *
     * @ORM\Column(name="refreshdate", type="integer", length=10, nullable=false)
     */
    private $refreshdate;

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
     * @var integer
     *
     * @ORM\Column(name="eid", type="integer", length=10, nullable=false)
     */
    private $eid;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", length=100, nullable=false)
     */
    private $region;

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
     * @var string
     *
     * @ORM\Column(name="shopindustry", type="string", length=100, nullable=false)
     */
    private $shopindustry;

    /**
     * @var integer
     *
     * @ORM\Column(name="shoptype", type="integer", length=100, nullable=false)
     */
    private $shoptype;

    /**
     * @var integer
     *
     * @ORM\Column(name="officelevel", type="integer", length=100, nullable=false)
     */
    private $officelevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="officetype", type="integer", length=100, nullable=false)
     */
    private $officetype;

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
     * @return Rent
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
     * @return Rent
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
     * Set lpmc
     *
     * @param string $lpmc
     * @return Rent
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
     * Set louxing
     *
     * @param integer $louxing
     * @return Rent
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
     * Set fdname
     *
     * @param string $fdname
     * @return Rent
     */
    public function setFdname($fdname)
    {
        $this->fdname = $fdname;
    
        return $this;
    }

    /**
     * Get fdname
     *
     * @return string 
     */
    public function getFdname()
    {
        return $this->fdname;
    }

    /**
     * Set fdtel
     *
     * @param string $fdtel
     * @return Rent
     */
    public function setFdtel($fdtel)
    {
        $this->fdtel = $fdtel;
    
        return $this;
    }

    /**
     * Get fdtel
     *
     * @return string 
     */
    public function getFdtel()
    {
        return $this->fdtel;
    }

    /**
     * Set fdnote
     *
     * @param string $fdnote
     * @return Rent
     */
    public function setFdnote($fdnote)
    {
        $this->fdnote = $fdnote;
    
        return $this;
    }

    /**
     * Get fdnote
     *
     * @return string 
     */
    public function getFdnote()
    {
        return $this->fdnote;
    }

    /**
     * Set czj
     *
     * @param float $czj
     * @return Rent
     */
    public function setCzj($czj)
    {
        $this->czj = $czj;
    
        return $this;
    }

    /**
     * Get czj
     *
     * @return float 
     */
    public function getCzj()
    {
        return $this->czj;
    }

    /**
     * Set mj
     *
     * @param float $mj
     * @return Rent
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
     * Set fl
     *
     * @param integer $fl
     * @return Rent
     */
    public function setFl($fl)
    {
        $this->fl = $fl;
    
        return $this;
    }

    /**
     * Get fl
     *
     * @return integer 
     */
    public function getFl()
    {
        return $this->fl;
    }

    /**
     * Set shi
     *
     * @param integer $shi
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * Set szlc
     *
     * @param string $szlc
     * @return Rent
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
     * @return Rent
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
     * Set zxcd
     *
     * @param integer $zxcd
     * @return Rent
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
     * @return Rent
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
     * Set fwpt
     *
     * @param string $fwpt
     * @return Rent
     */
    public function setFwpt($fwpt)
    {
        $this->fwpt = $fwpt;
    
        return $this;
    }

    /**
     * Get fwpt
     *
     * @return string 
     */
    public function getFwpt()
    {
        return $this->fwpt;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Rent
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
     * @return Rent
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
     * Set xingming
     *
     * @param string $xingming
     * @return Rent
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
     * Set address
     *
     * @param string $address
     * @return Rent
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
     * Set fkfs
     *
     * @param integer $fkfs
     * @return Rent
     */
    public function setFkfs($fkfs)
    {
        $this->fkfs = $fkfs;
    
        return $this;
    }

    /**
     * Get fkfs
     *
     * @return integer 
     */
    public function getFkfs()
    {
        return $this->fkfs;
    }

    /**
     * Set zlfs
     *
     * @param integer $zlfs
     * @return Rent
     */
    public function setZlfs($zlfs)
    {
        $this->zlfs = $zlfs;
    
        return $this;
    }

    /**
     * Get zlfs
     *
     * @return integer 
     */
    public function getZlfs()
    {
        return $this->zlfs;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Rent
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
     * @return Rent
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
     * Set author
     *
     * @param string $author
     * @return Rent
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
     * @return Rent
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
     * Set area
     *
     * @param integer $area
     * @return Rent
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
     * Set uid
     *
     * @param integer $uid
     * @return Rent
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
     * Set czlx
     *
     * @param integer $czlx
     * @return Rent
     */
    public function setCzlx($czlx)
    {
        $this->czlx = $czlx;
    
        return $this;
    }

    /**
     * Get czlx
     *
     * @return integer 
     */
    public function getCzlx()
    {
        return $this->czlx;
    }

    /**
     * Set top
     *
     * @param integer $top
     * @return Rent
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
     * Set jian
     *
     * @param integer $jian
     * @return Rent
     */
    public function setJian($jian)
    {
        $this->jian = $jian;
    
        return $this;
    }

    /**
     * Get jian
     *
     * @return integer 
     */
    public function getJian()
    {
        return $this->jian;
    }

    /**
     * Set valid
     *
     * @param integer $valid
     * @return Rent
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    
        return $this;
    }

    /**
     * Get valid
     *
     * @return integer 
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set office_lx
     *
     * @param integer $officeLx
     * @return Rent
     */
    public function setOfficeLx($officeLx)
    {
        $this->office_lx = $officeLx;
    
        return $this;
    }

    /**
     * Get office_lx
     *
     * @return integer 
     */
    public function getOfficeLx()
    {
        return $this->office_lx;
    }

    /**
     * Set office_jb
     *
     * @param integer $officeJb
     * @return Rent
     */
    public function setOfficeJb($officeJb)
    {
        $this->office_jb = $officeJb;
    
        return $this;
    }

    /**
     * Get office_jb
     *
     * @return integer 
     */
    public function getOfficeJb()
    {
        return $this->office_jb;
    }

    /**
     * Set office_ts
     *
     * @param string $officeTs
     * @return Rent
     */
    public function setOfficeTs($officeTs)
    {
        $this->office_ts = $officeTs;
    
        return $this;
    }

    /**
     * Get office_ts
     *
     * @return string 
     */
    public function getOfficeTs()
    {
        return $this->office_ts;
    }

    /**
     * Set shops_lx
     *
     * @param integer $shopsLx
     * @return Rent
     */
    public function setShopsLx($shopsLx)
    {
        $this->shops_lx = $shopsLx;
    
        return $this;
    }

    /**
     * Get shops_lx
     *
     * @return integer 
     */
    public function getShopsLx()
    {
        return $this->shops_lx;
    }

    /**
     * Set shops_hy
     *
     * @param string $shopsHy
     * @return Rent
     */
    public function setShopsHy($shopsHy)
    {
        $this->shops_hy = $shopsHy;
    
        return $this;
    }

    /**
     * Get shops_hy
     *
     * @return string 
     */
    public function getShopsHy()
    {
        return $this->shops_hy;
    }

    /**
     * Set shops_type
     *
     * @param integer $shopsType
     * @return Rent
     */
    public function setShopsType($shopsType)
    {
        $this->shops_type = $shopsType;
    
        return $this;
    }

    /**
     * Get shops_type
     *
     * @return integer 
     */
    public function getShopsType()
    {
        return $this->shops_type;
    }

    /**
     * Set shops_ts
     *
     * @param string $shopsTs
     * @return Rent
     */
    public function setShopsTs($shopsTs)
    {
        $this->shops_ts = $shopsTs;
    
        return $this;
    }

    /**
     * Get shops_ts
     *
     * @return string 
     */
    public function getShopsTs()
    {
        return $this->shops_ts;
    }

    /**
     * Set zjdate
     *
     * @param integer $zjdate
     * @return Rent
     */
    public function setZjdate($zjdate)
    {
        $this->zjdate = $zjdate;
    
        return $this;
    }

    /**
     * Get zjdate
     *
     * @return integer 
     */
    public function getZjdate()
    {
        return $this->zjdate;
    }

    /**
     * Set tujis
     *
     * @param string $tujis
     * @return Rent
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
     * Set srcid_
     *
     * @param integer $srcid
     * @return Rent
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
     * Set models
     *
     * @param string $models
     * @return Rent
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
     * Set cid
     *
     * @param integer $cid
     * @return Rent
     */
    public function setCid($cid)
    {
        $this->cid = $cid;
    
        return $this;
    }

    /**
     * Get cid
     *
     * @return integer 
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set job
     *
     * @param integer $job
     * @return Rent
     */
    public function setJob($job)
    {
        $this->job = $job;
    
        return $this;
    }

    /**
     * Get job
     *
     * @return integer 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Rent
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
     * Set fid
     *
     * @param integer $fid
     * @return Rent
     */
    public function setFid($fid)
    {
        $this->fid = $fid;
    
        return $this;
    }

    /**
     * Get fid
     *
     * @return integer 
     */
    public function getFid()
    {
        return $this->fid;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Rent
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
     * Set aid
     *
     * @param integer $aid
     * @return Rent
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
     * Set usertype
     *
     * @param integer $usertype
     * @return Rent
     */
    public function setUsertype($usertype)
    {
        $this->usertype = $usertype;
    
        return $this;
    }

    /**
     * Get usertype
     *
     * @return integer 
     */
    public function getUsertype()
    {
        return $this->usertype;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Rent
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
     * Set enddate
     *
     * @param integer $enddate
     * @return Rent
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    
        return $this;
    }

    /**
     * Get enddate
     *
     * @return integer 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set refreshdate
     *
     * @param integer $refreshdate
     * @return Rent
     */
    public function setRefreshdate($refreshdate)
    {
        $this->refreshdate = $refreshdate;
    
        return $this;
    }

    /**
     * Get refreshdate
     *
     * @return integer 
     */
    public function getRefreshdate()
    {
        return $this->refreshdate;
    }

    /**
     * Set cate_metro
     *
     * @param integer $cateMetro
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * Set eid
     *
     * @param integer $eid
     * @return Rent
     */
    public function setEid($eid)
    {
        $this->eid = $eid;
    
        return $this;
    }

    /**
     * Get eid
     *
     * @return integer 
     */
    public function getEid()
    {
        return $this->eid;
    }

    /**
     * Set region
     *
     * @param integer $region
     * @return Rent
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
     * Set lng
     *
     * @param float $lng
     * @return Rent
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
     * @return Rent
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
     * Set shopindustry
     *
     * @param string $shopindustry
     * @return Rent
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
     * Set shoptype
     *
     * @param integer $shoptype
     * @return Rent
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
     * Set officelevel
     *
     * @param integer $officelevel
     * @return Rent
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
     * Set officetype
     *
     * @param integer $officetype
     * @return Rent
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
     * Set attributes
     *
     * @param string $attributes
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
     * @return Rent
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
