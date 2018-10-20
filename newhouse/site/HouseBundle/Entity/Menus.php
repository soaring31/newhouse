<?php

namespace HouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menus
 *
 * @ORM\Table(name="menus", indexes={@ORM\Index(name="pid", columns={"pid"}), @ORM\Index(name="bundle", columns={"bundle"}), @ORM\Index(name="controller", columns={"controller"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class Menus
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
     * @ORM\Column(name="pid", type="integer", length=10, nullable=false)
     */
    private $pid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="bundle", type="string", length=50, nullable=false)
     */
    private $bundle;

    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=100, nullable=false)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=50, nullable=false)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=100, nullable=false)
     */
    private $identifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", length=3, nullable=false)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", length=1, nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hide", type="boolean", length=1, nullable=false)
     */
    private $is_hide;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_dev", type="boolean", length=1, nullable=false)
     */
    private $is_dev;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=20, nullable=false)
     */
    private $remark;

    /**
     * @var string
     *
     * @ORM\Column(name="bindmenu", type="string", length=100, nullable=false)
     */
    private $bindmenu;

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
     * @var boolean
     *
     * @ORM\Column(name="binary_tree", type="boolean", length=1, nullable=false)
     */
    private $binary_tree;

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
     * @var string
     *
     * @ORM\Column(name="urlparams", type="text", length=0, nullable=false)
     */
    private $urlparams;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", length=10, nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="category_models", type="string", length=100, nullable=false)
     */
    private $category_models;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", length=1, nullable=false)
     */
    private $issystem;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=100, nullable=false)
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100, nullable=false)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked", type="integer", length=10, nullable=false)
     */
    private $checked = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="attributes", type="string", length=10, nullable=false)
     */
    private $attributes;

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
     * Set pid
     *
     * @param integer $pid
     * @return Menus
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
     * Set name
     *
     * @param string $name
     * @return Menus
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
     * Set bundle
     *
     * @param string $bundle
     * @return Menus
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
     * Set controller
     *
     * @param string $controller
     * @return Menus
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
     * @return Menus
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
     * Set identifier
     *
     * @param string $identifier
     * @return Menus
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
     * Set sort
     *
     * @param integer $sort
     * @return Menus
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
     * Set level
     *
     * @param integer $level
     * @return Menus
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
     * Set title
     *
     * @param string $title
     * @return Menus
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
     * Set status
     *
     * @param boolean $status
     * @return Menus
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
     * Set is_hide
     *
     * @param boolean $isHide
     * @return Menus
     */
    public function setIsHide($isHide)
    {
        $this->is_hide = $isHide;

        return $this;
    }

    /**
     * Get is_hide
     *
     * @return boolean 
     */
    public function getIsHide()
    {
        return $this->is_hide;
    }

    /**
     * Set is_dev
     *
     * @param boolean $isDev
     * @return Menus
     */
    public function setIsDev($isDev)
    {
        $this->is_dev = $isDev;

        return $this;
    }

    /**
     * Get is_dev
     *
     * @return boolean 
     */
    public function getIsDev()
    {
        return $this->is_dev;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return Menus
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
     * Set bindmenu
     *
     * @param string $bindmenu
     * @return Menus
     */
    public function setBindmenu($bindmenu)
    {
        $this->bindmenu = $bindmenu;

        return $this;
    }

    /**
     * Get bindmenu
     *
     * @return string 
     */
    public function getBindmenu()
    {
        return $this->bindmenu;
    }

    /**
     * Set left_node
     *
     * @param integer $leftNode
     * @return Menus
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
     * @return Menus
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
     * Set binary_tree
     *
     * @param boolean $binaryTree
     * @return Menus
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
     * Set ename
     *
     * @param string $ename
     * @return Menus
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
     * @return Menus
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
     * Set urlparams
     *
     * @param string $urlparams
     * @return Menus
     */
    public function setUrlparams($urlparams)
    {
        $this->urlparams = $urlparams;

        return $this;
    }

    /**
     * Get urlparams
     *
     * @return string 
     */
    public function getUrlparams()
    {
        return $this->urlparams;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Menus
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
     * Set category_models
     *
     * @param string $categoryModels
     * @return Menus
     */
    public function setCategoryModels($categoryModels)
    {
        $this->category_models = $categoryModels;

        return $this;
    }

    /**
     * Get category_models
     *
     * @return string 
     */
    public function getCategoryModels()
    {
        return $this->category_models;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return Menus
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
     * Set genre
     *
     * @param string $genre
     * @return Menus
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Menus
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
     * Set checked
     *
     * @param integer $checked
     * @return Menus
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
     * Set attributes
     *
     * @param string $attributes
     * @return Menus
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
     * Set create_time
     *
     * @param integer $createTime
     * @return Menus
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
     * @return Menus
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
     * @return Menus
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
