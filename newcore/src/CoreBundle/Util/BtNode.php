<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月15日
 */
namespace CoreBundle\Util;

class BtNode
{
    // 左子树 "指针"
    public $mLchild;

    // 右子树 "指针"
    public $mRchild;

    // 结点数据域
    public $mData;

    // 左标志域，为1时表示mLchild "指向" 结点左孩子，为2表示 "指向" 结点直接前驱
    public $intLeftTag;

    // 右标志域，为1时表示mRchild "指向" 结点右孩子，为2表示 "指向" 结点直接后继
    public $intRightTag;
    
    public function __construct(){
        $this->mLchild = null;
        $this->mRchild = null;
        $this->mData = null;
        $this->intLeftTag = null;
        $this->intRightTag = null;
    }
}