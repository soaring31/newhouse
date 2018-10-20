<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HouseSemTel
 *
 * @ORM\Table(name="house_sem_tel", indexes={@ORM\Index(name="sem_id", columns={"sem_id"}), @ORM\Index(name="house_id", columns={"house_id"}), @ORM\Index(name="sem_type", columns={"sem_type"}), @ORM\Index(name="sem_platform", columns={"sem_platform"}), @ORM\Index(name="sem_channel", columns={"sem_channel"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class HouseSemTel
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
     * @ORM\Column(name="sem_id", type="integer", length=10, nullable=false)
     */
    private $sem_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="house_id", type="integer", length=10, nullable=false)
     */
    private $house_id;

    /**
     * @var string
     *
     * @ORM\Column(name="sem_type", type="string", length=100, nullable=false)
     */
    private $sem_type;

    /**
     * @var string
     *
     * @ORM\Column(name="sem_platform", type="string", length=100, nullable=false)
     */
    private $sem_platform;

    /**
     * @var string
     *
     * @ORM\Column(name="sem_channel", type="string", length=100, nullable=false)
     */
    private $sem_channel;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", length=2, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="bigcodetel", type="string", length=100, nullable=false)
     */
    private $bigcodetel;

    /**
     * @var string
     *
     * @ORM\Column(name="extcode", type="string", length=100, nullable=false)
     */
    private $extcode;

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
     * Set sem_id
     *
     * @param integer $semId
     * @return HouseSemTel
     */
    public function setSemId($semId)
    {
        $this->sem_id = $semId;

        return $this;
    }

    /**
     * Get sem_id
     *
     * @return integer 
     */
    public function getSemId()
    {
        return $this->sem_id;
    }

    /**
     * Set house_id
     *
     * @param integer $houseId
     * @return HouseSemTel
     */
    public function setHouseId($houseId)
    {
        $this->house_id = $houseId;

        return $this;
    }

    /**
     * Get house_id
     *
     * @return integer 
     */
    public function getHouseId()
    {
        return $this->house_id;
    }

    /**
     * Set sem_type
     *
     * @param string $semType
     * @return HouseSemTel
     */
    public function setSemType($semType)
    {
        $this->sem_type = $semType;

        return $this;
    }

    /**
     * Get sem_type
     *
     * @return string 
     */
    public function getSemType()
    {
        return $this->sem_type;
    }

    /**
     * Set sem_platform
     *
     * @param string $semPlatform
     * @return HouseSemTel
     */
    public function setSemPlatform($semPlatform)
    {
        $this->sem_platform = $semPlatform;

        return $this;
    }

    /**
     * Get sem_platform
     *
     * @return string 
     */
    public function getSemPlatform()
    {
        return $this->sem_platform;
    }

    /**
     * Set sem_channel
     *
     * @param string $semChannel
     * @return HouseSemTel
     */
    public function setSemChannel($semChannel)
    {
        $this->sem_channel = $semChannel;

        return $this;
    }

    /**
     * Get sem_channel
     *
     * @return string 
     */
    public function getSemChannel()
    {
        return $this->sem_channel;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return HouseSemTel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set bigcodetel
     *
     * @param string $bigcodetel
     * @return HouseSemTel
     */
    public function setBigcodetel($bigcodetel)
    {
        $this->bigcodetel = $bigcodetel;

        return $this;
    }

    /**
     * Get bigcodetel
     *
     * @return string 
     */
    public function getBigcodetel()
    {
        return $this->bigcodetel;
    }

    /**
     * Set extcode
     *
     * @param string $extcode
     * @return HouseSemTel
     */
    public function setExtcode($extcode)
    {
        $this->extcode = $extcode;

        return $this;
    }

    /**
     * Get extcode
     *
     * @return string 
     */
    public function getExtcode()
    {
        return $this->extcode;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return HouseSemTel
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
     * @return HouseSemTel
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
     * @return HouseSemTel
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
     * @return HouseSemTel
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
     * @return HouseSemTel
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
     * @return HouseSemTel
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
     * @return HouseSemTel
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
     * @return HouseSemTel
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
