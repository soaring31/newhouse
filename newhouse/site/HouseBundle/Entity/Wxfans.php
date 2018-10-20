<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxfans
 *
 * @ORM\Table(name="wxfans")
 * @ORM\Entity
 */
class Wxfans
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
     * @ORM\Column(name="openid", type="string", length=100, nullable=false)
     */
    private $openid;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=60, nullable=false)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=10, nullable=false)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=30, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=20, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=24, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="headimgurl", type="string", length=200, nullable=false)
     */
    private $headimgurl;

    /**
     * @var integer
     *
     * @ORM\Column(name="subscribe_time", type="integer", length=11, nullable=false)
     */
    private $subscribe_time;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=60, nullable=false)
     */
    private $remark;

    /**
     * @var string
     *
     * @ORM\Column(name="tagid_list", type="string", length=128, nullable=false)
     */
    private $tagid_list;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=50, nullable=false)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sync", type="boolean", length=1, nullable=false)
     */
    private $is_sync;

    /**
     * @var string
     *
     * @ORM\Column(name="tagname", type="string", length=50, nullable=false)
     */
    private $tagname;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

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
     * Set openid
     *
     * @param string $openid
     * @return Wxfans
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;
    
        return $this;
    }

    /**
     * Get openid
     *
     * @return string 
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Wxfans
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return Wxfans
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    
        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Wxfans
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Wxfans
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set province
     *
     * @param string $province
     * @return Wxfans
     */
    public function setProvince($province)
    {
        $this->province = $province;
    
        return $this;
    }

    /**
     * Get province
     *
     * @return string 
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set headimgurl
     *
     * @param string $headimgurl
     * @return Wxfans
     */
    public function setHeadimgurl($headimgurl)
    {
        $this->headimgurl = $headimgurl;
    
        return $this;
    }

    /**
     * Get headimgurl
     *
     * @return string 
     */
    public function getHeadimgurl()
    {
        return $this->headimgurl;
    }

    /**
     * Set subscribe_time
     *
     * @param integer $subscribeTime
     * @return Wxfans
     */
    public function setSubscribeTime($subscribeTime)
    {
        $this->subscribe_time = $subscribeTime;
    
        return $this;
    }

    /**
     * Get subscribe_time
     *
     * @return integer 
     */
    public function getSubscribeTime()
    {
        return $this->subscribe_time;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Wxfans
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    
        return $this;
    }

    /**
     * Get remark
     *
     * @return string 
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set tagid_list
     *
     * @param string $tagidList
     * @return Wxfans
     */
    public function setTagidList($tagidList)
    {
        $this->tagid_list = $tagidList;
    
        return $this;
    }

    /**
     * Get tagid_list
     *
     * @return string 
     */
    public function getTagidList()
    {
        return $this->tagid_list;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Wxfans
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set is_sync
     *
     * @param boolean $isSync
     * @return Wxfans
     */
    public function setIsSync($isSync)
    {
        $this->is_sync = $isSync;
    
        return $this;
    }

    /**
     * Get is_sync
     *
     * @return boolean 
     */
    public function getIsSync()
    {
        return $this->is_sync;
    }

    /**
     * Set tagname
     *
     * @param string $tagname
     * @return Wxfans
     */
    public function setTagname($tagname)
    {
        $this->tagname = $tagname;
    
        return $this;
    }

    /**
     * Get tagname
     *
     * @return string 
     */
    public function getTagname()
    {
        return $this->tagname;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return Wxfans
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
     * Set checked
     *
     * @param boolean $checked
     * @return Wxfans
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
     * @return Wxfans
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
     * @return Wxfans
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
     * @return Wxfans
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
     * @return Wxfans
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
     * @return Wxfans
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
     * @return Wxfans
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
     * @return Wxfans
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
