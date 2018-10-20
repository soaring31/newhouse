<?php
/**二叉树根
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月14日
*/
namespace CoreBundle\Functions;

class BinaryTreeRoot
{
    protected $key = NULL;
    // 当前节点的值
    protected $left = NULL;
    // 左子树
    protected $right = NULL;
    // 右子树
    /**
     * * 以指定的值构造二叉树，并指定左右子树 *
     * 
     * @param mixed $key
     *            节点的值.
     * @param mixed $left
     *            左子树节点.
     * @param mixed $right
     *            右子树节点.
     */
    public function __construct($key = NULL, $left = NULL, $right = NULL)
    {
        $this->key = $key;
        if ($key === NULL) {
            $this->left = NULL;
            $this->right = NULL;
        } elseif ($left === NULL) {
            $this->left = new BinaryTree();
            $this->right = new BinaryTree();
        } else {
            $this->left = $left;
            $this->right = $right;
        }
    }

    /**
     * 析构方法.
     */
    public function __destruct()
    {
        $this->key = NULL;
        $this->left = NULL;
        $this->right = NULL;
    }

    /**
     * 清空二叉树.
     */
    public function purge()
    {
        $this->key = NULL;
        $this->left = NULL;
        $this->right = NULL;
    }

    /**
     * 测试当前节点是否是叶节点.
     *
     * @return boolean 如果节点非空并且有两个空的子树时为真，否则为假.
     */
    public function isLeaf()
    {
        return ! $this->isEmpty() && $this->left->isEmpty() && $this->right->isEmpty();
    }

    /**
     * 测试节点是否为空
     *
     * @return boolean 如果节点为空返回真，否则为假.
     */
    public function isEmpty()
    {
        return $this->key === NULL;
    }

    /**
     * Key getter.
     *
     * @return mixed 节点的值.
     */
    public function getKey()
    {
        if ($this->isEmpty()) {
            return false;
        }
        return $this->key;
    }

    /**
     * 给节点指定Key值,节点必须为空
     *
     * @param mixed $object
     *            添加的Key值.
     */
    public function attachKey($obj)
    {
        if (! $this->isEmpty())
            return false;
        $this->key = $obj;
        $this->left = new BinaryTree();
        $this->right = new BinaryTree();
    }

    /**
     * 删除key值，使得节点为空.
     */
    public function detachKey()
    {
        if (! $this->isLeaf())
            return false;
        $result = $this->key;
        $this->key = NULL;
        $this->left = NULL;
        $this->right = NULL;
        return $result;
    }

    /**
     * 返回左子树
     *
     * @return object BinaryTree 当前节点的左子树.
     */
    public function getLeft()
    {
        if ($this->isEmpty())
            return false;
        return $this->left;
    }

    /**
     * 给当前结点添加左子树
     *
     * @param
     *            object BinaryTree $t 给当前节点添加的子树.
     */
    public function attachLeft(BinaryTree $t)
    {
        if ($this->isEmpty() || ! $this->left->isEmpty())
            return false;
        $this->left = $t;
    }

    /**
     * 删除左子树
     *
     * @return object BinaryTree 返回删除的左子树.
     */
    public function detachLeft()
    {
        if ($this->isEmpty())
            return false;
        $result = $this->left;
        $this->left = new BinaryTree();
        return $result;
    }

    /**
     * 返回当前节点的右子树
     *
     * @return object BinaryTree 当前节点的右子树.
     */
    public function getRight()
    {
        if ($this->isEmpty())
            return false;
        return $this->right;
    }

    /**
     * 给当前节点添加右子树
     *
     * @param
     *            object BinaryTree $t 需要添加的右子树.
     */
    public function attachRight(BinaryTree $t)
    {
        if ($this->isEmpty() || ! $this->right->isEmpty())
            return false;
        $this->right = $t;
    }

    /**
     * 删除右子树，并返回此右子树
     * 
     * @return object BinaryTree 删除的右子树.
     */
    public function detachRight()
    {
        if ($this->isEmpty())
            return false;
        $result = $this->right;
        $this->right = new BinaryTree();
        return $result;
    }

    /**
     * 先序遍历
     */
    public function preorderTraversal()
    {
        if ($this->isEmpty()) {
            return;
        }
        echo ' ', $this->getKey();
        $this->getLeft()->preorderTraversal();
        $this->getRight()->preorderTraversal();
    }

    /**
     * 中序遍历
     */
    public function inorderTraversal()
    {
        if ($this->isEmpty()) {
            return;
        }
        $this->getLeft()->preorderTraversal();
        echo ' ', $this->getKey();
        $this->getRight()->preorderTraversal();
    }

    /**
     * 后序遍历
     */
    public function postorderTraversal()
    {
        if ($this->isEmpty()) {
            return;
        }
        $this->getLeft()->preorderTraversal();
        $this->getRight()->preorderTraversal();
        echo ' ', $this->getKey();
    }
}