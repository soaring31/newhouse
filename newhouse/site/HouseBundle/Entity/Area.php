<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Area
 *
 * @ORM\Table(name="area", indexes={@ORM\Index(name="name", columns={"name"}), @ORM\Index(name="pid", columns={"pid"}), @ORM\Index(name="level", columns={"level"})})
 * @ORM\Entity
 */
class Area
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
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var integer
     *
     * @ORM\Column(name="pid", type="integer", length=10, nullable=false)
     */
    private $pid;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", length=10, nullable=false)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="citycode", type="integer", length=10, nullable=false)
     */
    private $citycode;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="smallint", length=3, nullable=false)
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="notes", type="integer", length=10, nullable=false)
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=100, nullable=false)
     */
    private $map;

    /**
     * @var string
     *
     * @ORM\Column(name="polyline", type="text", length=0, nullable=false)
     */
    private $polyline;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="integer", length=10, nullable=false)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", length=2, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="theme", type="integer", length=10, nullable=false)
     */
    private $theme;

    /**
     * @var integer
     *
     * @ORM\Column(name="main", type="smallint", length=2, nullable=false)
     */
    private $main;

    /**
     * @var integer
     *
     * @ORM\Column(name="oid", type="integer", length=10, nullable=false)
     */
    private $oid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="binary_tree", type="boolean", nullable=false)
     */
    private $binary_tree;

    /**
     * @var integer
     *
     * @ORM\Column(name="left_node", type="integer", nullable=false)
     */
    private $left_node;

    /**
     * @var integer
     *
     * @ORM\Column(name="right_node", type="integer", nullable=false)
     */
    private $right_node;

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
     * @return Area
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
     * Set sort
     *
     * @param integer $sort
     * @return Area
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
     * Set pid
     *
     * @param integer $pid
     * @return Area
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set code
     *
     * @param integer $code
     * @return Area
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set citycode
     *
     * @param integer $citycode
     * @return Area
     */
    public function setCitycode($citycode)
    {
        $this->citycode = $citycode;

        return $this;
    }

    /**
     * Get citycode
     *
     * @return integer 
     */
    public function getCitycode()
    {
        return $this->citycode;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Area
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set notes
     *
     * @param integer $notes
     * @return Area
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return integer 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Area
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
     * Set polyline
     *
     * @param string $polyline
     * @return Area
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
     * Set checked
     *
     * @param integer $checked
     * @return Area
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
     * Set type
     *
     * @param integer $type
     * @return Area
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
     * Set theme
     *
     * @param integer $theme
     * @return Area
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return integer 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set main
     *
     * @param integer $main
     * @return Area
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return integer 
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set oid
     *
     * @param integer $oid
     * @return Area
     */
    public function setOid($oid)
    {
        $this->oid = $oid;

        return $this;
    }

    /**
     * Get oid
     *
     * @return integer 
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set binary_tree
     *
     * @param boolean $binaryTree
     * @return Area
     */
    public function setBinaryTree($binaryTree)
    {
        $this->binary_tree = $binaryTree;

        return $this;
    }

    /**
     * Get binary_tree
     *
     * @return boolean 
     */
    public function getBinaryTree()
    {
        return $this->binary_tree;
    }

    /**
     * Set left_node
     *
     * @param integer $leftNode
     * @return Area
     */
    public function setLeftNode($leftNode)
    {
        $this->left_node = $leftNode;

        return $this;
    }

    /**
     * Get left_node
     *
     * @return integer 
     */
    public function getLeftNode()
    {
        return $this->left_node;
    }

    /**
     * Set right_node
     *
     * @param integer $rightNode
     * @return Area
     */
    public function setRightNode($rightNode)
    {
        $this->right_node = $rightNode;

        return $this;
    }

    /**
     * Get right_node
     *
     * @return integer 
     */
    public function getRightNode()
    {
        return $this->right_node;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Area
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
     * @return Area
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
     * @return Area
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
     * @return Area
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
     * @return Area
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
     * @return Area
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
