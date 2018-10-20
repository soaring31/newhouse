<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelFormAttr
 *
 * @ORM\Table(name="model_form_attr", indexes={@ORM\Index(name="model_form_id", columns={"model_form_id"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class ModelFormAttr
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
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=50, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="attr", type="text", length=0, nullable=false)
     */
    private $attr;

    /**
     * @var string
     *
     * @ORM\Column(name="choices", type="text", length=0, nullable=false)
     */
    private $choices;

    /**
     * @var boolean
     *
     * @ORM\Column(name="required", type="boolean", length=1, nullable=false)
     */
    private $required = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="entitypath", type="string", length=100, nullable=false)
     */
    private $entitypath;

    /**
     * @var string
     *
     * @ORM\Column(name="property", type="string", length=100, nullable=false)
     */
    private $property;

    /**
     * @var integer
     *
     * @ORM\Column(name="model_form_id", type="integer", length=10, nullable=false)
     */
    private $model_form_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", length=1, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="validate_rule", type="string", length=100, nullable=false)
     */
    private $validate_rule;

    /**
     * @var integer
     *
     * @ORM\Column(name="validate_time", type="integer", length=2, nullable=false)
     */
    private $validate_time;

    /**
     * @var string
     *
     * @ORM\Column(name="error_info", type="string", length=100, nullable=false)
     */
    private $error_info;

    /**
     * @var string
     *
     * @ORM\Column(name="auto_type", type="string", length=100, nullable=false)
     */
    private $auto_type;

    /**
     * @var string
     *
     * @ORM\Column(name="auto_rule", type="string", length=100, nullable=false)
     */
    private $auto_rule;

    /**
     * @var integer
     *
     * @ORM\Column(name="isonly", type="smallint", length=2, nullable=false)
     */
    private $isonly;

    /**
     * @var string
     *
     * @ORM\Column(name="query_builder", type="string", length=200, nullable=false)
     */
    private $query_builder;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=100, nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="g_type", type="string", length=100, nullable=false)
     */
    private $g_type;

    /**
     * @var string
     *
     * @ORM\Column(name="iswatermark", type="string", length=100, nullable=false)
     */
    private $iswatermark;

    /**
     * @var string
     *
     * @ORM\Column(name="dealhtml", type="string", length=24, nullable=false)
     */
    private $dealhtml;

    /**
     * @var string
     *
     * @ORM\Column(name="dealhtmltags", type="string", length=255, nullable=false)
     */
    private $dealhtmltags;

    /**
     * @var string
     *
     * @ORM\Column(name="bundle", type="string", length=64, nullable=false)
     */
    private $bundle;

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
     * @return ModelFormAttr
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
     * Set type
     *
     * @param string $type
     * @return ModelFormAttr
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return ModelFormAttr
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set attr
     *
     * @param string $attr
     * @return ModelFormAttr
     */
    public function setAttr($attr)
    {
        $this->attr = $attr;
    
        return $this;
    }

    /**
     * Get attr
     *
     * @return string 
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * Set choices
     *
     * @param string $choices
     * @return ModelFormAttr
     */
    public function setChoices($choices)
    {
        $this->choices = $choices;
    
        return $this;
    }

    /**
     * Get choices
     *
     * @return string 
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Set required
     *
     * @param boolean $required
     * @return ModelFormAttr
     */
    public function setRequired($required)
    {
        $this->required = $required;
    
        return $this;
    }

    /**
     * Get required
     *
     * @return boolean 
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set entitypath
     *
     * @param string $entitypath
     * @return ModelFormAttr
     */
    public function setEntitypath($entitypath)
    {
        $this->entitypath = $entitypath;
    
        return $this;
    }

    /**
     * Get entitypath
     *
     * @return string 
     */
    public function getEntitypath()
    {
        return $this->entitypath;
    }

    /**
     * Set property
     *
     * @param string $property
     * @return ModelFormAttr
     */
    public function setProperty($property)
    {
        $this->property = $property;
    
        return $this;
    }

    /**
     * Get property
     *
     * @return string 
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set model_form_id
     *
     * @param integer $modelFormId
     * @return ModelFormAttr
     */
    public function setModelFormId($modelFormId)
    {
        $this->model_form_id = $modelFormId;
    
        return $this;
    }

    /**
     * Get model_form_id
     *
     * @return integer 
     */
    public function getModelFormId()
    {
        return $this->model_form_id;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return ModelFormAttr
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
     * Set status
     *
     * @param boolean $status
     * @return ModelFormAttr
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
     * Set validate_rule
     *
     * @param string $validateRule
     * @return ModelFormAttr
     */
    public function setValidateRule($validateRule)
    {
        $this->validate_rule = $validateRule;
    
        return $this;
    }

    /**
     * Get validate_rule
     *
     * @return string 
     */
    public function getValidateRule()
    {
        return $this->validate_rule;
    }

    /**
     * Set validate_time
     *
     * @param integer $validateTime
     * @return ModelFormAttr
     */
    public function setValidateTime($validateTime)
    {
        $this->validate_time = $validateTime;
    
        return $this;
    }

    /**
     * Get validate_time
     *
     * @return integer 
     */
    public function getValidateTime()
    {
        return $this->validate_time;
    }

    /**
     * Set error_info
     *
     * @param string $errorInfo
     * @return ModelFormAttr
     */
    public function setErrorInfo($errorInfo)
    {
        $this->error_info = $errorInfo;
    
        return $this;
    }

    /**
     * Get error_info
     *
     * @return string 
     */
    public function getErrorInfo()
    {
        return $this->error_info;
    }

    /**
     * Set auto_type
     *
     * @param string $autoType
     * @return ModelFormAttr
     */
    public function setAutoType($autoType)
    {
        $this->auto_type = $autoType;
    
        return $this;
    }

    /**
     * Get auto_type
     *
     * @return string 
     */
    public function getAutoType()
    {
        return $this->auto_type;
    }

    /**
     * Set auto_rule
     *
     * @param string $autoRule
     * @return ModelFormAttr
     */
    public function setAutoRule($autoRule)
    {
        $this->auto_rule = $autoRule;
    
        return $this;
    }

    /**
     * Get auto_rule
     *
     * @return string 
     */
    public function getAutoRule()
    {
        return $this->auto_rule;
    }

    /**
     * Set isonly
     *
     * @param integer $isonly
     * @return ModelFormAttr
     */
    public function setIsonly($isonly)
    {
        $this->isonly = $isonly;
    
        return $this;
    }

    /**
     * Get isonly
     *
     * @return integer 
     */
    public function getIsonly()
    {
        return $this->isonly;
    }

    /**
     * Set query_builder
     *
     * @param string $queryBuilder
     * @return ModelFormAttr
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->query_builder = $queryBuilder;
    
        return $this;
    }

    /**
     * Get query_builder
     *
     * @return string 
     */
    public function getQueryBuilder()
    {
        return $this->query_builder;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return ModelFormAttr
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set g_type
     *
     * @param string $gType
     * @return ModelFormAttr
     */
    public function setGType($gType)
    {
        $this->g_type = $gType;
    
        return $this;
    }

    /**
     * Get g_type
     *
     * @return string 
     */
    public function getGType()
    {
        return $this->g_type;
    }

    /**
     * Set iswatermark
     *
     * @param string $iswatermark
     * @return ModelFormAttr
     */
    public function setIswatermark($iswatermark)
    {
        $this->iswatermark = $iswatermark;
    
        return $this;
    }

    /**
     * Get iswatermark
     *
     * @return string 
     */
    public function getIswatermark()
    {
        return $this->iswatermark;
    }

    /**
     * Set dealhtml
     *
     * @param string $dealhtml
     * @return ModelFormAttr
     */
    public function setDealhtml($dealhtml)
    {
        $this->dealhtml = $dealhtml;
    
        return $this;
    }

    /**
     * Get dealhtml
     *
     * @return string 
     */
    public function getDealhtml()
    {
        return $this->dealhtml;
    }

    /**
     * Set dealhtmltags
     *
     * @param string $dealhtmltags
     * @return ModelFormAttr
     */
    public function setDealhtmltags($dealhtmltags)
    {
        $this->dealhtmltags = $dealhtmltags;
    
        return $this;
    }

    /**
     * Get dealhtmltags
     *
     * @return string 
     */
    public function getDealhtmltags()
    {
        return $this->dealhtmltags;
    }

    /**
     * Set bundle
     *
     * @param string $bundle
     * @return ModelFormAttr
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
     * Set checked
     *
     * @param boolean $checked
     * @return ModelFormAttr
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
     * @return ModelFormAttr
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
     * @return ModelFormAttr
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
     * @return ModelFormAttr
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
     * @return ModelFormAttr
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
     * @return ModelFormAttr
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
     * @return ModelFormAttr
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
