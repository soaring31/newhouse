<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventListen
 *
 * @ORM\Table(name="event_listen")
 * @ORM\Entity
 */
class EventListen
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
     * @ORM\Column(name="name", type="string", length=48, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="op", type="string", length=24, nullable=false)
     */
    private $op;

    /**
     * @var integer
     *
     * @ORM\Column(name="op_order", type="smallint", length=2, nullable=false)
     */
    private $op_order;

    /**
     * @var string
     *
     * @ORM\Column(name="model_name", type="string", length=48, nullable=false)
     */
    private $model_name;

    /**
     * @var string
     *
     * @ORM\Column(name="model_path", type="string", length=255, nullable=false)
     */
    private $model_path;

    /**
     * @var string
     *
     * @ORM\Column(name="bind_name", type="string", length=48, nullable=false)
     */
    private $bind_name;

    /**
     * @var string
     *
     * @ORM\Column(name="bind_path", type="string", length=255, nullable=false)
     */
    private $bind_path;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=48, nullable=false)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="condtion", type="text", length=255, nullable=false)
     */
    private $condtion;

    /**
     * @var string
     *
     * @ORM\Column(name="map_param", type="text", length=0, nullable=false)
     */
    private $map_param;

    /**
     * @var string
     *
     * @ORM\Column(name="map_where", type="text", length=0, nullable=false)
     */
    private $map_where;

    /**
     * @var integer
     *
     * @ORM\Column(name="map_type", type="smallint", length=2, nullable=false)
     */
    private $map_type;

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
     * @return EventListen
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
     * Set op
     *
     * @param string $op
     * @return EventListen
     */
    public function setOp($op)
    {
        $this->op = $op;
    
        return $this;
    }

    /**
     * Get op
     *
     * @return string 
     */
    public function getOp()
    {
        return $this->op;
    }

    /**
     * Set op_order
     *
     * @param integer $opOrder
     * @return EventListen
     */
    public function setOpOrder($opOrder)
    {
        $this->op_order = $opOrder;
    
        return $this;
    }

    /**
     * Get op_order
     *
     * @return integer 
     */
    public function getOpOrder()
    {
        return $this->op_order;
    }

    /**
     * Set model_name
     *
     * @param string $modelName
     * @return EventListen
     */
    public function setModelName($modelName)
    {
        $this->model_name = $modelName;
    
        return $this;
    }

    /**
     * Get model_name
     *
     * @return string 
     */
    public function getModelName()
    {
        return $this->model_name;
    }

    /**
     * Set model_path
     *
     * @param string $modelPath
     * @return EventListen
     */
    public function setModelPath($modelPath)
    {
        $this->model_path = $modelPath;
    
        return $this;
    }

    /**
     * Get model_path
     *
     * @return string 
     */
    public function getModelPath()
    {
        return $this->model_path;
    }

    /**
     * Set bind_name
     *
     * @param string $bindName
     * @return EventListen
     */
    public function setBindName($bindName)
    {
        $this->bind_name = $bindName;
    
        return $this;
    }

    /**
     * Get bind_name
     *
     * @return string 
     */
    public function getBindName()
    {
        return $this->bind_name;
    }

    /**
     * Set bind_path
     *
     * @param string $bindPath
     * @return EventListen
     */
    public function setBindPath($bindPath)
    {
        $this->bind_path = $bindPath;
    
        return $this;
    }

    /**
     * Get bind_path
     *
     * @return string 
     */
    public function getBindPath()
    {
        return $this->bind_path;
    }

    /**
     * Set field
     *
     * @param string $field
     * @return EventListen
     */
    public function setField($field)
    {
        $this->field = $field;
    
        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set condtion
     *
     * @param string $condtion
     * @return EventListen
     */
    public function setCondtion($condtion)
    {
        $this->condtion = $condtion;
    
        return $this;
    }

    /**
     * Get condtion
     *
     * @return string 
     */
    public function getCondtion()
    {
        return $this->condtion;
    }

    /**
     * Set map_param
     *
     * @param string $mapParam
     * @return EventListen
     */
    public function setMapParam($mapParam)
    {
        $this->map_param = $mapParam;
    
        return $this;
    }

    /**
     * Get map_param
     *
     * @return string 
     */
    public function getMapParam()
    {
        return $this->map_param;
    }

    /**
     * Set map_where
     *
     * @param string $mapWhere
     * @return EventListen
     */
    public function setMapWhere($mapWhere)
    {
        $this->map_where = $mapWhere;
    
        return $this;
    }

    /**
     * Get map_where
     *
     * @return string 
     */
    public function getMapWhere()
    {
        return $this->map_where;
    }

    /**
     * Set map_type
     *
     * @param integer $mapType
     * @return EventListen
     */
    public function setMapType($mapType)
    {
        $this->map_type = $mapType;
    
        return $this;
    }

    /**
     * Get map_type
     *
     * @return integer 
     */
    public function getMapType()
    {
        return $this->map_type;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return EventListen
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
     * @return EventListen
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
     * @return EventListen
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
     * @return EventListen
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
     * @return EventListen
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
     * @return EventListen
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
     * @return EventListen
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
     * @return EventListen
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
