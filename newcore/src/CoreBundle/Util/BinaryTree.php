<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月15日
 */
namespace CoreBundle\Util;

class BinaryTree
{
    // 根结点
    public $mRoot;

    // 根据先序遍历录入的二叉树数据
    public $mPBTdata = null;

    /**
     * 构造方法,初始化建立二叉树
     *
     * @param array $btdata
     *            根据先序遍历录入的二叉树的数据，一维数组，每一个元素代表二叉树一个结点值,扩充结点值为''[长度为0的字符串]
     * @return void
     */
    public function __construct()
    {        
        $this->mRoot = null;
    }
    
    public function setMPBTdata($btdata)
    {
        if(is_array($btdata))
        {
            foreach($btdata as $item)
            {
                if(is_object($item))
                    $this->mPBTdata[$item->getId()] = $item;
                else
                    $this->mPBTdata[$item['id']] = $item;
            }
        }
        //$this->mPBTdata = $btdata;
        $this->getPreorderTraversalCreate($this->mRoot);
    }


    /**
     * 按先序遍历方式建立二叉树
     *
     * @param
     *            BTNode 二叉树结点，按引用方式传递
     * @return void
     */
    public function getPreorderTraversalCreate(&$btnode)
    {
        $elem = array_shift($this->mPBTdata);
        if ($elem === '') {
            $btnode = null;
        } else {
            if ($elem === null) {
                return;
            } else {
                $btnode = new BtNode();
                $btnode->mData = $elem;
                $btnode->intLeftTag = $elem['left_node'];
                $btnode->intRightTag = $elem['right_node'];

                $this->getPreorderTraversalCreate($btnode->mLchild);
                $this->getPreorderTraversalCreate($btnode->mRchild);
            }
        }
    }
    
    public function getPreorderTraversalCreateaaa(&$btnode)
    {
        foreach($this->mPBTdata as $item)
        {
            $btnode = new BtNode();
            
            if(is_object($item))
            {
                //左节点
                if($item->getLeftNode()>0&&isset($this->mPBTdata[$item->getLeftNode()]))
                {
                    $btnode->intLeftTag = $item->getLeftNode();
                    $btnode->mLchild = $this->mPBTdata[$item->getLeftNode()];
                }
                
                //右节点
                if($item->getRightNode()>0&&isset($this->mPBTdata[$item->getRightNode()]))
                {
                    $btnode->mRchild = $item->getRightNode();
                    $btnode->intRightTag = $this->mPBTdata[$item->getRightNode()];
                }                
            }
            
            if(is_array($item))
            {
                //左节点
                if($item['left_node']>0&&isset($this->mPBTdata[$item['left_node']]))
                {
                    $btnode->intLeftTag = $item['left_node'];
                    $btnode->mLchild = $this->mPBTdata[$item['left_node']];
                }
                
                //右节点
                if($item['right_node']>0&&isset($this->mPBTdata[$item['right_node']]))
                {
                    $btnode->intRightTag = $item['right_node'];
                    $btnode->mRchild = $this->mPBTdata[$item['right_node']];
                }
            }
                
            dump($btnode);
            die();
        }
    }
    
    public function getPreorderTraversalCreateback(&$btnode)
    {
        $elem = array_shift($this->mPBTdata);
        if ($elem === '') {
            $btnode = null;
        } else 
            if ($elem === null) {
                return;
            } else {
                $btnode = new BtNode();
                $btnode->mData = $elem;
                $this->getPreorderTraversalCreate($btnode->mLchild);
                $this->getPreorderTraversalCreate($btnode->mRchild);
            }
    }

    /**
     * 判断二叉树是否为空
     *
     * @return boolean 如果二叉树不空返回true,否则返回false
     *        
     */
    public function getIsEmpty()
    {
        if ($this->mRoot instanceof BTNode) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 将二叉树置空
     *
     * @return void
     */
    public function setBinaryTreeNull()
    {
        $this->mRoot = null;
    }

    /**
     * 按先序遍历二叉树
     *
     * @param BTNode $rootnode
     *            遍历过程中的根结点
     * @param array $btarr
     *            接收值的数组变量，按引用方式传递
     * @return void
     */
    public function getPreorderTraversal($rootnode, &$btarr)
    {
        if ($rootnode != null) {
            $btarr[] = $rootnode->mData;
            $this->getPreorderTraversal($rootnode->mLchild, $btarr);
            $this->getPreorderTraversal($rootnode->mRchild, $btarr);
        }
    }
    
    public function test($rootnode, &$btarr)
    {
        dump($rootnode);
        die();
        if (is_array($rootnode))
        {
            $btarr[] = $rootnode->mData;
            $this->getPreorderTraversal($rootnode->mLchild, $btarr);
            $this->getPreorderTraversal($rootnode->mRchild, $btarr);
        }
    }

    /**
     * 先序遍历的非递归算法
     *
     * @param BTNode $objRootNode
     *            二叉树根节点
     * @param array $arrBTdata
     *            接收值的数组变量，按引用方式传递
     * @return void
     */
    public function getPreorderTraversalNoRecursion($objRootNode, &$arrBTdata)
    {
        if ($objRootNode instanceof BTNode) {
            $objNode = $objRootNode;
            $objStack = new StackLinked();
            do {
                $arrBTdata[] = $objNode->mData;
                $objRNode = $objNode->mRchild;
                if ($objRNode != null) {
                    $objStack->getPushStack($objRNode);
                }
                $objNode = $objNode->mLchild;
                if ($objNode == null) {
                    $objStack->getPopStack($objNode);
                }
            } while ($objNode != null);
        } else {
            $arrBTdata = array();
        }
    }

    /**
     * 中序遍历二叉树
     *
     * @param BTNode $objRootNode
     *            过程中的根节点
     * @param array $arrBTdata
     *            接收值的数组变量,按引用方式传递
     * @return void
     */
    public function getInorderTraversal($objRootNode, &$arrBTdata)
    {
        if ($objRootNode != null) {
            $this->getInorderTraversal($objRootNode->mLchild, $arrBTdata);
            $arrBTdata[] = $objRootNode->mData;
            $this->getInorderTraversal($objRootNode->mRchild, $arrBTdata);
        }
    }

    /**
     * 中序遍历的非递归算法
     *
     * @param BTNode $objRootNode
     *            二叉树根结点
     * @param array $arrBTdata
     *            接收值的数组变量，按引用方式传递
     * @return void
     */
    public function getInorderTraversalNoRecursion($objRootNode, &$arrBTdata)
    {
        if ($objRootNode instanceof BTNode) {
            $objNode = $objRootNode;
            $objStack = new StackLinked();
            // 中序遍历左子树及访问根节点
            do {
                while ($objNode != null) {
                    $objStack->getPushStack($objNode);
                    $objNode = $objNode->mLchild;
                }
                $objStack->getPopStack($objNode);
                $arrBTdata[] = $objNode->mData;
                $objNode = $objNode->mRchild;
            } while (! $objStack->getIsEmpty());
            // 中序遍历右子树
            do {
                while ($objNode != null) {
                    $objStack->getPushStack($objNode);
                    $objNode = $objNode->mLchild;
                }
                $objStack->getPopStack($objNode);
                $arrBTdata[] = $objNode->mData;
                $objNode = $objNode->mRchild;
            } while (! $objStack->getIsEmpty());
        } else {
            $arrBTdata = array();
        }
    }

    /**
     * 后序遍历二叉树
     *
     * @param BTNode $objRootNode
     *            遍历过程中的根结点
     * @param array $arrBTdata
     *            接收值的数组变量，引用方式传递
     * @return void
     */
    public function getPostorderTraversal($objRootNode, &$arrBTdata)
    {
        if ($objRootNode != null) {
            $this->getPostorderTraversal($objRootNode->mLchild, $arrBTdata);
            $this->getPostorderTraversal($objRootNode->mRchild, $arrBTdata);
            $arrBTdata[] = $objRootNode->mData;
        }
    }

    /**
     * 后序遍历非递归算法
     *
     * BTNode $objRootNode 二叉树根节点
     * array $arrBTdata 接收值的数组变量，按引用方式传递
     * void
     */
    public function getPostorderTraversalNoRecursion($objRootNode, &$arrBTdata)
    {
        if ($objRootNode instanceof BTNode) {
            $objNode = $objRootNode;
            $objStack = new StackLinked();
            $objTagStack = new StackLinked();
            $tag = 1;
            do {
                while ($objNode != null) {
                    $objStack->getPushStack($objNode);
                    $objTagStack->getPushStack(1);
                    $objNode = $objNode->mLchild;
                }
                $objTagStack->getPopStack($tag);
                $objTagStack->getPushStack($tag);
                if ($tag == 1) {
                    $objStack->getPopStack($objNode);
                    $objStack->getPushStack($objNode);
                    $objNode = $objNode->mRchild;
                    $objTagStack->getPopStack($tag);
                    $objTagStack->getPushStack(2);
                } else {
                    $objStack->getPopStack($objNode);
                    $arrBTdata[] = $objNode->mData;
                    $objTagStack->getPopStack($tag);
                    $objNode = null;
                }
            } while (! $objStack->getIsEmpty());
        } else {
            $arrBTdata = array();
        }
    }

    /**
     * 层次遍历二叉树
     *
     * @param BTNode $objRootNode二叉树根节点            
     * @param array $arrBTdata
     *            接收值的数组变量，按引用方式传递
     * @return void
     */
    public function getLevelorderTraversal($objRootNode, &$arrBTdata)
    {
        if ($objRootNode instanceof BTNode) {
            $objNode = $objRootNode;
            $objQueue = new QueueLinked();
            $objQueue->getInsertElem($objNode);
            while (! $objQueue->getIsEmpty()) {
                $objQueue->getDeleteElem($objNode);
                $arrBTdata[] = $objNode->mData;
                if ($objNode->mLchild != null) {
                    $objQueue->getInsertElem($objNode->mLchild);
                }
                if ($objNode->mRchild != null) {
                    $objQueue->getInsertElem($objNode->mRchild);
                }
            }
        } else {
            $arrBTdata = array();
        }
    }

    /**
     * 求二叉树叶子结点的个数
     *
     * @param BTNode $objRootNode
     *            二叉树根节点
     * @return int 参数传递错误返回-1
     *        
     */
    public function getLeafNodeCount($objRootNode)
    {
        if ($objRootNode instanceof BTNode) {
            $intLeafNodeCount = 0;
            $objNode = $objRootNode;
            $objStack = new StackLinked();
            do {
                if ($objNode->mLchild == null && $objNode->mRchild == null) {
                    $intLeafNodeCount ++;
                }
                $objRNode = $objNode->mRchild;
                if ($objRNode != null) {
                    $objStack->getPushStack($objRNode);
                }
                $objNode = $objNode->mLchild;
                if ($objNode == null) {
                    $objStack->getPopStack($objNode);
                }
            } while ($objNode != null);
            return $intLeafNodeCount;
        } else {
            return - 1;
        }
    }

    /**
     * 求二叉树的深度
     *
     * @param BTNode $objRootNode
     *            二叉树根节点
     * @return int 参数传递错误返回-1
     */
    public function getBinaryTreeDepth($objRootNode)
    {
        if ($objRootNode instanceof BTNode) {
            $objNode = $objRootNode;
            $objQueue = new QueueLinked();
            $intBinaryTreeDepth = 0;
            $objQueue->getInsertElem($objNode);
            $objLevel = $objNode;
            while (! $objQueue->getIsEmpty()) {
                $objQueue->getDeleteElem($objNode);
                if ($objNode->mLchild != null) {
                    $objQueue->getInsertElem($objNode->mLchild);
                }
                if ($objNode->mRchild != null) {
                    $objQueue->getInsertElem($objNode->mRchild);
                }
                if ($objLevel == $objNode) {
                    $intBinaryTreeDepth ++;
                    $objLevel = @$objQueue->mRear->mElem;
                }
            }
            return $intBinaryTreeDepth;
        } else {
            return - 1;
        }
    }
}

// $data = array('A','B','D','','','E','','G','','','C','F','','','');
// $bt = $this->get('core.binarytree');
// $bt->setMPBTdata($data);
 
// dump("二叉树结构：");
// dump($bt);
// $btarr=array();

// dump("先序递归遍历二叉树：");
// $bt->getPreorderTraversal($bt->mRoot,$btarr);
// dump($btarr);

// dump("先序非递归遍历二叉树：");
// $arrBTdata=array();
// $bt->getPreorderTraversalNoRecursion($bt->mRoot,$arrBTdata);
// dump($arrBTdata);

// dump("中序递归遍历二叉树：");
// $arrBTdata=array();
// $bt->getInorderTraversal($bt->mRoot,$arrBTdata);
// dump($arrBTdata);

// dump("中序非递归遍历二叉树：");
// $arrBTdata=array();
// $bt->getInorderTraversalNoRecursion($bt->mRoot,$arrBTdata);
// dump($arrBTdata);

// dump("后序递归遍历二叉树：");
// $arrBTdata=array();
// $bt->getPostorderTraversal($bt->mRoot,$arrBTdata);
// dump($arrBTdata);

// dump("后序非递归遍历二叉树:");
// $arrBTdata=array();
// $bt->getPostorderTraversalNoRecursion($bt->mRoot,$arrBTdata);
// dump($arrBTdata);

// dump("按层次遍历二叉树：");
// $arrBTdata=array();
// $bt->getLevelorderTraversal($bt->mRoot,$arrBTdata);
// dump($arrBTdata);

// dump("叶子结点的个数为：".$bt->getLeafNodeCount($bt->mRoot));
// dump("二叉树深度为:".$bt->getBinaryTreeDepth($bt->mRoot));
// dump("判断二叉树是否为空：");
// dump($bt->getIsEmpty());
// dump("将二叉树置空后：");
// $bt->setBinaryTreeNull();
// dump($bt);
// echo "</pre>";
// die();