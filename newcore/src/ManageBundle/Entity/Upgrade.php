<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Upgrade
 *
 * @ORM\Table(name="upgrade")
 * @ORM\Entity
 */
class Upgrade
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
     * @ORM\Column(name="addinfo", type="string", length=100, nullable=false)
     */
    private $addinfo;

    /**
     * @var integer
     *
     * @ORM\Column(name="usertype", type="smallint", length=2, nullable=false)
     */
    private $usertype;

    /**
     * @var string
     *
     * @ORM\Column(name="dpjj", type="string", length=100, nullable=false)
     */
    private $dpjj;

    /**
     * @var string
     *
     * @ORM\Column(name="dpgg", type="string", length=100, nullable=false)
     */
    private $dpgg;

    /**
     * @var string
     *
     * @ORM\Column(name="qq", type="string", length=100, nullable=false)
     */
    private $qq;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", length=0, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="cpltd", type="string", length=100, nullable=false)
     */
    private $cpltd;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=100, nullable=false)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="text", length=0, nullable=false)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="caddress", type="string", length=100, nullable=false)
     */
    private $caddress;

    /**
     * @var string
     *
     * @ORM\Column(name="companyjs", type="text", length=0, nullable=false)
     */
    private $companyjs;

    /**
     * @var string
     *
     * @ORM\Column(name="nicename", type="string", length=100, nullable=false)
     */
    private $nicename;

    /**
     * @var string
     *
     * @ORM\Column(name="cynx", type="string", length=100, nullable=false)
     */
    private $cynx;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=100, nullable=false)
     */
    private $tel;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", length=100, nullable=false)
     */
    private $region;

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
     * Set addinfo
     *
     * @param string $addinfo
     * @return Upgrade
     */
    public function setAddinfo($addinfo)
    {
        $this->addinfo = $addinfo;
    
        return $this;
    }

    /**
     * Get addinfo
     *
     * @return string 
     */
    public function getAddinfo()
    {
        return $this->addinfo;
    }

    /**
     * Set usertype
     *
     * @param integer $usertype
     * @return Upgrade
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
     * Set dpjj
     *
     * @param string $dpjj
     * @return Upgrade
     */
    public function setDpjj($dpjj)
    {
        $this->dpjj = $dpjj;
    
        return $this;
    }

    /**
     * Get dpjj
     *
     * @return string 
     */
    public function getDpjj()
    {
        return $this->dpjj;
    }

    /**
     * Set dpgg
     *
     * @param string $dpgg
     * @return Upgrade
     */
    public function setDpgg($dpgg)
    {
        $this->dpgg = $dpgg;
    
        return $this;
    }

    /**
     * Get dpgg
     *
     * @return string 
     */
    public function getDpgg()
    {
        return $this->dpgg;
    }

    /**
     * Set qq
     *
     * @param string $qq
     * @return Upgrade
     */
    public function setQq($qq)
    {
        $this->qq = $qq;
    
        return $this;
    }

    /**
     * Get qq
     *
     * @return string 
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Upgrade
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
     * Set cpltd
     *
     * @param string $cpltd
     * @return Upgrade
     */
    public function setCpltd($cpltd)
    {
        $this->cpltd = $cpltd;
    
        return $this;
    }

    /**
     * Get cpltd
     *
     * @return string 
     */
    public function getCpltd()
    {
        return $this->cpltd;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Upgrade
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Upgrade
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    
        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set caddress
     *
     * @param string $caddress
     * @return Upgrade
     */
    public function setCaddress($caddress)
    {
        $this->caddress = $caddress;
    
        return $this;
    }

    /**
     * Get caddress
     *
     * @return string 
     */
    public function getCaddress()
    {
        return $this->caddress;
    }

    /**
     * Set companyjs
     *
     * @param string $companyjs
     * @return Upgrade
     */
    public function setCompanyjs($companyjs)
    {
        $this->companyjs = $companyjs;
    
        return $this;
    }

    /**
     * Get companyjs
     *
     * @return string 
     */
    public function getCompanyjs()
    {
        return $this->companyjs;
    }

    /**
     * Set nicename
     *
     * @param string $nicename
     * @return Upgrade
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
     * Set cynx
     *
     * @param string $cynx
     * @return Upgrade
     */
    public function setCynx($cynx)
    {
        $this->cynx = $cynx;
    
        return $this;
    }

    /**
     * Get cynx
     *
     * @return string 
     */
    public function getCynx()
    {
        return $this->cynx;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Upgrade
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
     * Set uid
     *
     * @param integer $uid
     * @return Upgrade
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
     * Set region
     *
     * @param integer $region
     * @return Upgrade
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
     * Set checked
     *
     * @param boolean $checked
     * @return Upgrade
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
     * @return Upgrade
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
     * @return Upgrade
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
     * @return Upgrade
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
     * @return Upgrade
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
     * @return Upgrade
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
     * @return Upgrade
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
     * @return Upgrade
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
