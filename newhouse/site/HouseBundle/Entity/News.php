<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news", indexes={@ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="source_side", columns={"source_side"}), @ORM\Index(name="name", columns={"name"}), @ORM\Index(name="area", columns={"area"})})
 * @ORM\Entity
 */
class News
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
     * @ORM\Column(name="uid", type="integer", length=10, nullable=false)
     */
    private $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="users", type="integer", length=10, nullable=false)
     */
    private $users;

    /**
     * @var integer
     *
     * @ORM\Column(name="source_side", type="integer", length=5, nullable=false)
     */
    private $source_side;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var integer
     *
     * @ORM\Column(name="comnum", type="integer", length=10, nullable=false)
     */
    private $comnum;

    /**
     * @var integer
     *
     * @ORM\Column(name="srcid_", type="integer", length=11, nullable=false)
     */
    private $srcid_;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="smallint", length=2, nullable=false)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="houses", type="integer", length=10, nullable=false)
     */
    private $houses;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", length=10, nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="text", length=100, nullable=false)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=50, nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=50, nullable=false)
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", length=10, nullable=false)
     */
    private $vote;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", length=0, nullable=false)
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="text", length=0, nullable=false)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks", type="integer", length=10, nullable=false)
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", length=100, nullable=false)
     */
    private $area;

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
     * Set uid
     *
     * @param integer $uid
     * @return News
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
     * Set users
     *
     * @param integer $users
     * @return News
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return integer 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set source_side
     *
     * @param integer $sourceSide
     * @return News
     */
    public function setSourceSide($sourceSide)
    {
        $this->source_side = $sourceSide;

        return $this;
    }

    /**
     * Get source_side
     *
     * @return integer 
     */
    public function getSourceSide()
    {
        return $this->source_side;
    }

    /**
     * Set models
     *
     * @param string $models
     * @return News
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
     * Set comnum
     *
     * @param integer $comnum
     * @return News
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
     * Set srcid_
     *
     * @param integer $srcid
     * @return News
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
     * Set name
     *
     * @param string $name
     * @return News
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
     * Set content
     *
     * @param string $content
     * @return News
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
     * Set checked
     *
     * @param integer $checked
     * @return News
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
     * Set houses
     *
     * @param integer $houses
     * @return News
     */
    public function setHouses($houses)
    {
        $this->houses = $houses;

        return $this;
    }

    /**
     * Get houses
     *
     * @return integer 
     */
    public function getHouses()
    {
        return $this->houses;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return News
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return News
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
     * Set author
     *
     * @param string $author
     * @return News
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
     * @return News
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
     * Set vote
     *
     * @param integer $vote
     * @return News
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
     * Set abstract
     *
     * @param string $abstract
     * @return News
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
     * Set keywords
     *
     * @param string $keywords
     * @return News
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
     * Set clicks
     *
     * @param integer $clicks
     * @return News
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
     * Set area
     *
     * @param integer $area
     * @return News
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
     * Set attributes
     *
     * @param string $attributes
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
     * @return News
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
