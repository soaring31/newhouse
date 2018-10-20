<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InterHousescomment
 *
 * @ORM\Table(name="inter_housescomment")
 * @ORM\Entity
 */
class InterHousescomment
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
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="overall", type="string", length=100, nullable=false)
     */
    private $overall;

    /**
     * @var string
     *
     * @ORM\Column(name="environment", type="string", length=100, nullable=false)
     */
    private $environment;

    /**
     * @var string
     *
     * @ORM\Column(name="charms", type="string", length=100, nullable=false)
     */
    private $charms;

    /**
     * @var string
     *
     * @ORM\Column(name="traffic", type="string", length=100, nullable=false)
     */
    private $traffic;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=100, nullable=false)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=100, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="aid", type="string", length=100, nullable=false)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=100, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=100, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="toid", type="string", length=100, nullable=false)
     */
    private $toid;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var string
     *
     * @ORM\Column(name="vote", type="string", length=100, nullable=false)
     */
    private $vote;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="text", length=0, nullable=false)
     */
    private $user_agent;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=100, nullable=false)
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="comment_type", type="integer", length=11, nullable=false)
     */
    private $comment_type;

    /**
     * @var string
     *
     * @ORM\Column(name="nickename", type="string", length=100, nullable=false)
     */
    private $nickename;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_source", type="string", length=100, nullable=false)
     */
    private $comment_source;

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
     * Set content
     *
     * @param string $content
     * @return InterHousescomment
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
     * Set overall
     *
     * @param string $overall
     * @return InterHousescomment
     */
    public function setOverall($overall)
    {
        $this->overall = $overall;

        return $this;
    }

    /**
     * Get overall
     *
     * @return string 
     */
    public function getOverall()
    {
        return $this->overall;
    }

    /**
     * Set environment
     *
     * @param string $environment
     * @return InterHousescomment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get environment
     *
     * @return string 
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set charms
     *
     * @param string $charms
     * @return InterHousescomment
     */
    public function setCharms($charms)
    {
        $this->charms = $charms;

        return $this;
    }

    /**
     * Get charms
     *
     * @return string 
     */
    public function getCharms()
    {
        return $this->charms;
    }

    /**
     * Set traffic
     *
     * @param string $traffic
     * @return InterHousescomment
     */
    public function setTraffic($traffic)
    {
        $this->traffic = $traffic;

        return $this;
    }

    /**
     * Get traffic
     *
     * @return string 
     */
    public function getTraffic()
    {
        return $this->traffic;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return InterHousescomment
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return InterHousescomment
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set aid
     *
     * @param string $aid
     * @return InterHousescomment
     */
    public function setAid($aid)
    {
        $this->aid = $aid;

        return $this;
    }

    /**
     * Get aid
     *
     * @return string 
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return InterHousescomment
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return InterHousescomment
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return InterHousescomment
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
     * Set toid
     *
     * @param string $toid
     * @return InterHousescomment
     */
    public function setToid($toid)
    {
        $this->toid = $toid;

        return $this;
    }

    /**
     * Get toid
     *
     * @return string 
     */
    public function getToid()
    {
        return $this->toid;
    }

    /**
     * Set models
     *
     * @param string $models
     * @return InterHousescomment
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
     * Set vote
     *
     * @param string $vote
     * @return InterHousescomment
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return string 
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set user_agent
     *
     * @param string $userAgent
     * @return InterHousescomment
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
     * Set source
     *
     * @param string $source
     * @return InterHousescomment
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
     * Set comment_type
     *
     * @param integer $commentType
     * @return InterHousescomment
     */
    public function setCommentType($commentType)
    {
        $this->comment_type = $commentType;

        return $this;
    }

    /**
     * Get comment_type
     *
     * @return integer 
     */
    public function getCommentType()
    {
        return $this->comment_type;
    }

    /**
     * Set nickename
     *
     * @param string $nickename
     * @return InterHousescomment
     */
    public function setNickename($nickename)
    {
        $this->nickename = $nickename;

        return $this;
    }

    /**
     * Get nickename
     *
     * @return string 
     */
    public function getNickename()
    {
        return $this->nickename;
    }

    /**
     * Set comment_source
     *
     * @param string $commentSource
     * @return InterHousescomment
     */
    public function setCommentSource($commentSource)
    {
        $this->comment_source = $commentSource;

        return $this;
    }

    /**
     * Get comment_source
     *
     * @return string 
     */
    public function getCommentSource()
    {
        return $this->comment_source;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return InterHousescomment
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
     * @return InterHousescomment
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
     * @return InterHousescomment
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
     * @return InterHousescomment
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
     * @return InterHousescomment
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
     * @return InterHousescomment
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
     * @return InterHousescomment
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
     * @return InterHousescomment
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
