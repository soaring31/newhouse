<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventController
 *
 * @ORM\Table(name="event_controller")
 * @ORM\Entity
 */
class EventController
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
     * @ORM\Column(name="op", type="string", length=50, nullable=false)
     */
    private $op;

    /**
     * @var integer
     *
     * @ORM\Column(name="opType", type="smallint", length=2, nullable=false)
     */
    private $opType;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=200, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=100, nullable=false)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=100, nullable=false)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="bindService", type="string", length=100, nullable=false)
     */
    private $bindService;

    /**
     * @var string
     *
     * @ORM\Column(name="condtion", type="text", length=0, nullable=false)
     */
    private $condtion;

    /**
     * @var string
     *
     * @ORM\Column(name="mapParam", type="text", length=0, nullable=false)
     */
    private $mapParam;

    /**
     * @var string
     *
     * @ORM\Column(name="mapWhere", type="text", length=0, nullable=false)
     */
    private $mapWhere;

    /**
     * @var integer
     *
     * @ORM\Column(name="mapType", type="smallint", length=2, nullable=false)
     */
    private $mapType;

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
     * @return EventController
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
     * @return EventController
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
     * Set opType
     *
     * @param integer $opType
     * @return EventController
     */
    public function setOpType($opType)
    {
        $this->opType = $opType;
    
        return $this;
    }

    /**
     * Get opType
     *
     * @return integer 
     */
    public function getOpType()
    {
        return $this->opType;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return EventController
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set controller
     *
     * @param string $controller
     * @return EventController
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    
        return $this;
    }

    /**
     * Get controller
     *
     * @return string 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return EventController
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set bindService
     *
     * @param string $bindService
     * @return EventController
     */
    public function setBindService($bindService)
    {
        $this->bindService = $bindService;
    
        return $this;
    }

    /**
     * Get bindService
     *
     * @return string 
     */
    public function getBindService()
    {
        return $this->bindService;
    }

    /**
     * Set condtion
     *
     * @param string $condtion
     * @return EventController
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
     * Set mapParam
     *
     * @param string $mapParam
     * @return EventController
     */
    public function setMapParam($mapParam)
    {
        $this->mapParam = $mapParam;
    
        return $this;
    }

    /**
     * Get mapParam
     *
     * @return string 
     */
    public function getMapParam()
    {
        return $this->mapParam;
    }

    /**
     * Set mapWhere
     *
     * @param string $mapWhere
     * @return EventController
     */
    public function setMapWhere($mapWhere)
    {
        $this->mapWhere = $mapWhere;
    
        return $this;
    }

    /**
     * Get mapWhere
     *
     * @return string 
     */
    public function getMapWhere()
    {
        return $this->mapWhere;
    }

    /**
     * Set mapType
     *
     * @param integer $mapType
     * @return EventController
     */
    public function setMapType($mapType)
    {
        $this->mapType = $mapType;
    
        return $this;
    }

    /**
     * Get mapType
     *
     * @return integer 
     */
    public function getMapType()
    {
        return $this->mapType;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return EventController
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
     * @return EventController
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
     * @return EventController
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
     * @return EventController
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
     * @return EventController
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
     * @return EventController
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
     * @return EventController
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
     * @return EventController
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
