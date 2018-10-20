<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="name", columns={"name"}), @ORM\Index(name="pid", columns={"pid"}), @ORM\Index(name="ename", columns={"ename"}), @ORM\Index(name="keywords", columns={"keywords"})})
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="sort", type="smallint", length=5, nullable=false)
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
     * @ORM\Column(name="level", type="smallint", length=3, nullable=false)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="ename", type="string", length=100, nullable=false)
     */
    private $ename;

    /**
     * @var string
     *
     * @ORM\Column(name="models", type="string", length=100, nullable=false)
     */
    private $models;

    /**
     * @var boolean
     *
     * @ORM\Column(name="binary_tree", type="boolean", length=1, nullable=false)
     */
    private $binary_tree;

    /**
     * @var integer
     *
     * @ORM\Column(name="left_node", type="integer", length=10, nullable=false)
     */
    private $left_node;

    /**
     * @var integer
     *
     * @ORM\Column(name="right_node", type="integer", length=10, nullable=false)
     */
    private $right_node;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=50, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="sqlstr", type="string", length=100, nullable=false)
     */
    private $sqlstr;

    /**
     * @var string
     *
     * @ORM\Column(name="cate_action", type="string", length=100, nullable=false)
     */
    private $cate_action;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=100, nullable=false)
     */
    private $remark;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_next", type="boolean", length=1, nullable=false)
     */
    private $is_next;

    /**
     * @var integer
     *
     * @ORM\Column(name="form_id", type="integer", length=10, nullable=false)
     */
    private $form_id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", length=1, nullable=false)
     */
    private $checked = '1';

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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Set level
     *
     * @param integer $level
     * @return Category
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
     * Set ename
     *
     * @param string $ename
     * @return Category
     */
    public function setEname($ename)
    {
        $this->ename = $ename;
    
        return $this;
    }

    /**
     * Get ename
     *
     * @return string 
     */
    public function getEname()
    {
        return $this->ename;
    }

    /**
     * Set models
     *
     * @param string $models
     * @return Category
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
     * Set binary_tree
     *
     * @param boolean $binaryTree
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Set keywords
     *
     * @param string $keywords
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set sqlstr
     *
     * @param string $sqlstr
     * @return Category
     */
    public function setSqlstr($sqlstr)
    {
        $this->sqlstr = $sqlstr;
    
        return $this;
    }

    /**
     * Get sqlstr
     *
     * @return string 
     */
    public function getSqlstr()
    {
        return $this->sqlstr;
    }

    /**
     * Set cate_action
     *
     * @param string $cateAction
     * @return Category
     */
    public function setCateAction($cateAction)
    {
        $this->cate_action = $cateAction;
    
        return $this;
    }

    /**
     * Get cate_action
     *
     * @return string 
     */
    public function getCateAction()
    {
        return $this->cate_action;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Category
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
     * Set is_next
     *
     * @param boolean $isNext
     * @return Category
     */
    public function setIsNext($isNext)
    {
        $this->is_next = $isNext;
    
        return $this;
    }

    /**
     * Get is_next
     *
     * @return boolean 
     */
    public function getIsNext()
    {
        return $this->is_next;
    }

    /**
     * Set form_id
     *
     * @param integer $formId
     * @return Category
     */
    public function setFormId($formId)
    {
        $this->form_id = $formId;
    
        return $this;
    }

    /**
     * Get form_id
     *
     * @return integer 
     */
    public function getFormId()
    {
        return $this->form_id;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
