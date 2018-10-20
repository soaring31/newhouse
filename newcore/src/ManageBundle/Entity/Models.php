<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Models
 *
 * @ORM\Table(name="models", indexes={@ORM\Index(name="name", columns={"name"}), @ORM\Index(name="service_name", columns={"service_name"})})
 * @ORM\Entity
 */
class Models
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", length=100, nullable=false)
     */
    private $service_name;

    /**
     * @var string
     *
     * @ORM\Column(name="bundle", type="string", length=100, nullable=false)
     */
    private $bundle;

    /**
     * @var string
     *
     * @ORM\Column(name="relation", type="string", length=100, nullable=false)
     */
    private $relation;

    /**
     * @var string
     *
     * @ORM\Column(name="search_key", type="string", length=100, nullable=false)
     */
    private $search_key;

    /**
     * @var string
     *
     * @ORM\Column(name="search_list", type="string", length=100, nullable=false)
     */
    private $search_list;

    /**
     * @var string
     *
     * @ORM\Column(name="engine_type", type="string", length=100, nullable=false)
     */
    private $engine_type = 'MyISAM';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", length=1, nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="autoup", type="boolean", length=1, nullable=false)
     */
    private $autoup;

    /**
     * @var integer
     *
     * @ORM\Column(name="mode", type="smallint", length=2, nullable=false)
     */
    private $mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", length=3, nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_binary", type="boolean", length=1, nullable=false)
     */
    private $is_binary;

    /**
     * @var string
     *
     * @ORM\Column(name="associated", type="string", length=255, nullable=false)
     */
    private $associated;

    /**
     * @var integer
     *
     * @ORM\Column(name="structure", type="smallint", length=3, nullable=false)
     */
    private $structure;

    /**
     * @var integer
     *
     * @ORM\Column(name="attribute_table", type="integer", length=10, nullable=false)
     */
    private $attribute_table;

    /**
     * @var integer
     *
     * @ORM\Column(name="models_plan", type="integer", length=6, nullable=false)
     */
    private $models_plan;

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
     * @return Models
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
     * Set title
     *
     * @param string $title
     * @return Models
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set service_name
     *
     * @param string $serviceName
     * @return Models
     */
    public function setServiceName($serviceName)
    {
        $this->service_name = $serviceName;
    
        return $this;
    }

    /**
     * Get service_name
     *
     * @return string 
     */
    public function getServiceName()
    {
        return $this->service_name;
    }

    /**
     * Set bundle
     *
     * @param string $bundle
     * @return Models
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    
        return $this;
    }

    /**
     * Get bundle
     *
     * @return string 
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Set relation
     *
     * @param string $relation
     * @return Models
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
    
        return $this;
    }

    /**
     * Get relation
     *
     * @return string 
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set search_key
     *
     * @param string $searchKey
     * @return Models
     */
    public function setSearchKey($searchKey)
    {
        $this->search_key = $searchKey;
    
        return $this;
    }

    /**
     * Get search_key
     *
     * @return string 
     */
    public function getSearchKey()
    {
        return $this->search_key;
    }

    /**
     * Set search_list
     *
     * @param string $searchList
     * @return Models
     */
    public function setSearchList($searchList)
    {
        $this->search_list = $searchList;
    
        return $this;
    }

    /**
     * Get search_list
     *
     * @return string 
     */
    public function getSearchList()
    {
        return $this->search_list;
    }

    /**
     * Set engine_type
     *
     * @param string $engineType
     * @return Models
     */
    public function setEngineType($engineType)
    {
        $this->engine_type = $engineType;
    
        return $this;
    }

    /**
     * Get engine_type
     *
     * @return string 
     */
    public function getEngineType()
    {
        return $this->engine_type;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Models
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Models
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
     * Set autoup
     *
     * @param boolean $autoup
     * @return Models
     */
    public function setAutoup($autoup)
    {
        $this->autoup = $autoup;
    
        return $this;
    }

    /**
     * Get autoup
     *
     * @return boolean 
     */
    public function getAutoup()
    {
        return $this->autoup;
    }

    /**
     * Set mode
     *
     * @param integer $mode
     * @return Models
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    
        return $this;
    }

    /**
     * Get mode
     *
     * @return integer 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Models
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
     * Set is_binary
     *
     * @param boolean $isBinary
     * @return Models
     */
    public function setIsBinary($isBinary)
    {
        $this->is_binary = $isBinary;
    
        return $this;
    }

    /**
     * Get is_binary
     *
     * @return boolean 
     */
    public function getIsBinary()
    {
        return $this->is_binary;
    }

    /**
     * Set associated
     *
     * @param string $associated
     * @return Models
     */
    public function setAssociated($associated)
    {
        $this->associated = $associated;
    
        return $this;
    }

    /**
     * Get associated
     *
     * @return string 
     */
    public function getAssociated()
    {
        return $this->associated;
    }

    /**
     * Set structure
     *
     * @param integer $structure
     * @return Models
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    
        return $this;
    }

    /**
     * Get structure
     *
     * @return integer 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set attribute_table
     *
     * @param integer $attributeTable
     * @return Models
     */
    public function setAttributeTable($attributeTable)
    {
        $this->attribute_table = $attributeTable;
    
        return $this;
    }

    /**
     * Get attribute_table
     *
     * @return integer 
     */
    public function getAttributeTable()
    {
        return $this->attribute_table;
    }

    /**
     * Set models_plan
     *
     * @param integer $modelsPlan
     * @return Models
     */
    public function setModelsPlan($modelsPlan)
    {
        $this->models_plan = $modelsPlan;
    
        return $this;
    }

    /**
     * Get models_plan
     *
     * @return integer 
     */
    public function getModelsPlan()
    {
        return $this->models_plan;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Models
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
     * @return Models
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
     * @return Models
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
     * @return Models
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
     * @return Models
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
     * @return Models
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
     * @return Models
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
