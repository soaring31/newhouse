<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userinfo
 *
 * @ORM\Table(name="userinfo", indexes={@ORM\Index(name="region", columns={"region"}), @ORM\Index(name="cid", columns={"cid"}), @ORM\Index(name="usertype", columns={"usertype"}), @ORM\Index(name="groupid", columns={"groupid"}), @ORM\Index(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class Userinfo
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
     * @ORM\Column(name="region", type="integer", length=10, nullable=false)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="cynx", type="smallint", length=2, nullable=false)
     */
    private $cynx;

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
     * @ORM\Column(name="crents", type="integer", length=10, nullable=false)
     */
    private $crents;

    /**
     * @var integer
     *
     * @ORM\Column(name="csales", type="integer", length=10, nullable=false)
     */
    private $csales;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=10, nullable=false)
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="yongjin", type="integer", length=10, nullable=false)
     */
    private $yongjin;

    /**
     * @var integer
     *
     * @ORM\Column(name="cid", type="integer", length=10, nullable=false)
     */
    private $cid;

    /**
     * @var integer
     *
     * @ORM\Column(name="fxpid", type="integer", length=10, nullable=false)
     */
    private $fxpid;

    /**
     * @var integer
     *
     * @ORM\Column(name="pnum", type="integer", length=10, nullable=false)
     */
    private $pnum;

    /**
     * @var integer
     *
     * @ORM\Column(name="unnum", type="integer", length=10, nullable=false)
     */
    private $unnum;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", length=0, nullable=false)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="usertype", type="integer", length=10, nullable=false)
     */
    private $usertype;

    /**
     * @var integer
     *
     * @ORM\Column(name="groupid", type="integer", length=10, nullable=false)
     */
    private $groupid;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="usertplid", type="string", length=100, nullable=false)
     */
    private $usertplid;

    /**
     * @var string
     *
     * @ORM\Column(name="nicename", type="string", length=100, nullable=false)
     */
    private $nicename;

    /**
     * @var integer
     *
     * @ORM\Column(name="tops", type="integer", length=10, nullable=false)
     */
    private $tops;

    /**
     * @var integer
     *
     * @ORM\Column(name="refreshs", type="integer", length=10, nullable=false)
     */
    private $refreshs;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="duedate", type="integer", length=10, nullable=false)
     */
    private $duedate;

    /**
     * @var string
     *
     * @ORM\Column(name="weixin", type="text", length=0, nullable=false)
     */
    private $weixin;

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
     * Set region
     *
     * @param integer $region
     * @return Userinfo
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
     * Set cynx
     *
     * @param integer $cynx
     * @return Userinfo
     */
    public function setCynx($cynx)
    {
        $this->cynx = $cynx;
    
        return $this;
    }

    /**
     * Get cynx
     *
     * @return integer 
     */
    public function getCynx()
    {
        return $this->cynx;
    }

    /**
     * Set sales
     *
     * @param integer $sales
     * @return Userinfo
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
     * @return Userinfo
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
     * Set crents
     *
     * @param integer $crents
     * @return Userinfo
     */
    public function setCrents($crents)
    {
        $this->crents = $crents;
    
        return $this;
    }

    /**
     * Get crents
     *
     * @return integer 
     */
    public function getCrents()
    {
        return $this->crents;
    }

    /**
     * Set csales
     *
     * @param integer $csales
     * @return Userinfo
     */
    public function setCsales($csales)
    {
        $this->csales = $csales;
    
        return $this;
    }

    /**
     * Get csales
     *
     * @return integer 
     */
    public function getCsales()
    {
        return $this->csales;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Userinfo
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
     * Set yongjin
     *
     * @param integer $yongjin
     * @return Userinfo
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
     * Set cid
     *
     * @param integer $cid
     * @return Userinfo
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
     * Set fxpid
     *
     * @param integer $fxpid
     * @return Userinfo
     */
    public function setFxpid($fxpid)
    {
        $this->fxpid = $fxpid;
    
        return $this;
    }

    /**
     * Get fxpid
     *
     * @return integer 
     */
    public function getFxpid()
    {
        return $this->fxpid;
    }

    /**
     * Set pnum
     *
     * @param integer $pnum
     * @return Userinfo
     */
    public function setPnum($pnum)
    {
        $this->pnum = $pnum;
    
        return $this;
    }

    /**
     * Get pnum
     *
     * @return integer 
     */
    public function getPnum()
    {
        return $this->pnum;
    }

    /**
     * Set unnum
     *
     * @param integer $unnum
     * @return Userinfo
     */
    public function setUnnum($unnum)
    {
        $this->unnum = $unnum;
    
        return $this;
    }

    /**
     * Get unnum
     *
     * @return integer 
     */
    public function getUnnum()
    {
        return $this->unnum;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Userinfo
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set usertype
     *
     * @param integer $usertype
     * @return Userinfo
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
     * Set groupid
     *
     * @param integer $groupid
     * @return Userinfo
     */
    public function setGroupid($groupid)
    {
        $this->groupid = $groupid;
    
        return $this;
    }

    /**
     * Get groupid
     *
     * @return integer 
     */
    public function getGroupid()
    {
        return $this->groupid;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Userinfo
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
     * Set usertplid
     *
     * @param string $usertplid
     * @return Userinfo
     */
    public function setUsertplid($usertplid)
    {
        $this->usertplid = $usertplid;
    
        return $this;
    }

    /**
     * Get usertplid
     *
     * @return string 
     */
    public function getUsertplid()
    {
        return $this->usertplid;
    }

    /**
     * Set nicename
     *
     * @param string $nicename
     * @return Userinfo
     */
    public function setNicename($nicename)
    {
        $this->nicename = $nicename;
    
        return $this;
    }

    /**
     * Get nicename
     *
     * @return string 
     */
    public function getNicename()
    {
        return $this->nicename;
    }

    /**
     * Set tops
     *
     * @param integer $tops
     * @return Userinfo
     */
    public function setTops($tops)
    {
        $this->tops = $tops;
    
        return $this;
    }

    /**
     * Get tops
     *
     * @return integer 
     */
    public function getTops()
    {
        return $this->tops;
    }

    /**
     * Set refreshs
     *
     * @param integer $refreshs
     * @return Userinfo
     */
    public function setRefreshs($refreshs)
    {
        $this->refreshs = $refreshs;
    
        return $this;
    }

    /**
     * Get refreshs
     *
     * @return integer 
     */
    public function getRefreshs()
    {
        return $this->refreshs;
    }

    /**
     * Set checked
     *
     * @param integer $checked
     * @return Userinfo
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
     * Set duedate
     *
     * @param integer $duedate
     * @return Userinfo
     */
    public function setDuedate($duedate)
    {
        $this->duedate = $duedate;
    
        return $this;
    }

    /**
     * Get duedate
     *
     * @return integer 
     */
    public function getDuedate()
    {
        return $this->duedate;
    }

    /**
     * Set weixin
     *
     * @param string $weixin
     * @return Userinfo
     */
    public function setWeixin($weixin)
    {
        $this->weixin = $weixin;
    
        return $this;
    }

    /**
     * Get weixin
     *
     * @return string 
     */
    public function getWeixin()
    {
        return $this->weixin;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Userinfo
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
     * @return Userinfo
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
     * @return Userinfo
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
     * @return Userinfo
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
     * @return Userinfo
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
     * @return Userinfo
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
     * @return Userinfo
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
