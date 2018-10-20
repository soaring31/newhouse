<?php
/**
 * 二叉树
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月14日
 */
namespace CoreBundle\Functions;

/**
 * 二叉排序树的PHP实现
 */
class BinaryTree extends BinaryTreeRoot
{

    /**
     * 构造空的二叉排序树
     */
    public function __construct()
    {
        parent::__construct(NULL, NULL, NULL);
    }

    /**
     * 析构
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * 测试二叉排序树中是否包含参数所指定的值
     *
     * @param mixed $obj
     *            查找的值.
     * @return boolean True 如果存在于二叉排序树中则返回真，否则为假期
     */
    public function contains($obj)
    {
        if ($this->isEmpty())
            return false;
        $diff = $this->compare($obj);
        if ($diff == 0) {
            return true;
        } elseif ($diff < 0)
            return $this->getLeft()->contains($obj);
        else
            return $this->getRight()->contains($obj);
    }

    /**
     * 查找二叉排序树中参数所指定的值的位置
     *
     * @param mixed $obj
     *            查找的值.
     * @return boolean True 如果存在则返回包含此值的对象，否则为NULL
     */
    public function find($obj)
    {
        if ($this->isEmpty())
            return NULL;
        $diff = $this->compare($obj);
        if ($diff == 0)
            return $this->getKey();
        elseif ($diff < 0)
            return $this->getLeft()->find($obj);
        else
            return $this->getRight()->find($obj);
    }

    /**
     * 返回二叉排序树中的最小值
     * 
     * @return mixed 如果存在则返回最小值，否则返回NULL
     */
    public function findMin()
    {
        if ($this->isEmpty())
            return NULL;
        elseif ($this->getLeft()->isEmpty())
            return $this->getKey();
        else
            return $this->getLeft()->findMin();
    }

    /**
     * 返回二叉排序树中的最大值
     * 
     * @return mixed 如果存在则返回最大值，否则返回NULL
     */
    public function findMax()
    {
        if ($this->isEmpty())
            return NULL;
        elseif ($this->getRight()->isEmpty())
            return $this->getKey();
        else
            return $this->getRight()->findMax();
    }

    /**
     * 给二叉排序树插入指定值
     *
     * @param mixed $obj
     *            需要插入的值.
     *            如果指定的值在树中存在，则返回错误
     */
    public function insert($obj)
    {
        if ($this->isEmpty()) {
            $this->attachKey($obj);
        } else {
            $diff = $this->compare($obj);
            if ($diff == 0)
                die('argu error');
            if ($diff < 0)
                $this->getLeft()->insert($obj);
            else
                $this->getRight()->insert($obj);
        }
        $this->balance();
    }

    /**
     * 从二叉排序树中删除指定的值
     *
     * @param mixed $obj
     *            需要删除的值.
     */
    public function delete($obj)
    {
        if ($this->isEmpty())
            die();
        
        $diff = $this->compare($obj);
        if ($diff == 0) {
            if (! $this->getLeft()->isEmpty()) {
                $max = $this->getLeft()->findMax();
                $this->key = $max;
                $this->getLeft()->delete($max);
            } elseif (! $this->getRight()->isEmpty()) {
                $min = $this->getRight()->findMin();
                $this->key = $min;
                $this->getRight()->delete($min);
            } else
                $this->detachKey();
        } else 
            if ($diff < 0)
                $this->getLeft()->delete($obj);
            else
                $this->getRight()->delete($obj);
        $this->balance();
    }

    public function compare($obj)
    {
        return $obj - $this->getKey();
    }

    /**
     * Attaches the specified object as the key of this node.
     * The node must be initially empty.
     *
     * @param
     *            object IObject $obj The key to attach.
     *            @exception IllegalOperationException If this node is not empty.
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
     * Balances this node.
     * Does nothing in this class.
     */
    protected function balance()
    {}

    /**
     * Main program.
     *
     * @param array $args
     *            Command-line arguments.
     * @return integer Zero on success; non-zero on failure.
     */
    public function main($args)
    {
        $root = new BinaryTree();
        foreach ($args as $row) {
            $root->insert($row);
        }
        dump($root);
        return $root;
    }
}

// $root = BST::main(array(50, 3, 10, 5, 100, 56, 78));
// echo $root->findMax();
// $root->delete(100);
// echo $root->findMax();