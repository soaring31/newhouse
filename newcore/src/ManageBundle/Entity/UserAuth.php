<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserAuth
 *
 * @ORM\Table(name="user_auth")
 * @ORM\Entity
 */
class UserAuth
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
     * @ORM\Column(name="usertype", type="string", length=100, nullable=false)
     */
    private $usertype;

    /**
     * @var integer
     *
     * @ORM\Column(name="integral", type="smallint", length=2, nullable=false)
     */
    private $integral;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", length=10, nullable=false)
     */
    private $num;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=100, nullable=false)
     */
    private $remark;

    /**
     * @var integer
     *
     * @ORM\Column(name="form", type="integer", length=10, nullable=false)
     */
    private $form;

    /**
     * @var integer
     *
     * @ORM\Column(name="autochecked", type="smallint", length=2, nullable=false)
     */
    private $autochecked;

    /**
     * @var integer
     *
     * @ORM\Column(name="listen", type="smallint", length=2, nullable=false)
     */
    private $listen;

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
     * Set name
     *
     * @param string $name
     * @return UserAuth
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
     * @return UserAuth
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
     * Set usertype
     *
     * @param string $usertype
     * @return UserAuth
     */
    public function setUsertype($usertype)
    {
        $this->usertype = $usertype;
    
        return $this;
    }

    /**
     * Get usertype
     *
     * @return string 
     */
    public function getUsertype()
    {
        return $this->usertype;
    }

    /**
     * Set integral
     *
     * @param integer $integral
     * @return UserAuth
     */
    public function setIntegral($integral)
    {
        $this->integral = $integral;
    
        return $this;
    }

    /**
     * Get integral
     *
     * @return integer 
     */
    public function getIntegral()
    {
        return $this->integral;
    }

    /**
     * Set num
     *
     * @param integer $num
     * @return UserAuth
     */
    public function setNum($num)
    {
        $this->num = $num;
    
        return $this;
    }

    /**
     * Get num
     *
     * @return integer 
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return UserAuth
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
     * Set form
     *
     * @param integer $form
     * @return UserAuth
     */
    public function setForm($form)
    {
        $this->form = $form;
    
        return $this;
    }

    /**
     * Get form
     *
     * @return integer 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set autochecked
     *
     * @param integer $autochecked
     * @return UserAuth
     */
    public function setAutochecked($autochecked)
    {
        $this->autochecked = $autochecked;
    
        return $this;
    }

    /**
     * Get autochecked
     *
     * @return integer 
     */
    public function getAutochecked()
    {
        return $this->autochecked;
    }

    /**
     * Set listen
     *
     * @param integer $listen
     * @return UserAuth
     */
    public function setListen($listen)
    {
        $this->listen = $listen;
    
        return $this;
    }

    /**
     * Get listen
     *
     * @return integer 
     */
    public function getListen()
    {
        return $this->listen;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return UserAuth
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
     * @return UserAuth
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
     * @return UserAuth
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
     * @return UserAuth
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
     * @return UserAuth
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
     * @return UserAuth
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
     * @return UserAuth
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
     * @return UserAuth
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
