<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月15日
*/
namespace CoreBundle\Util;

class Linknode
{
    public $mElem;  
    public $mNext;  
    public function __construct(){  
        $this->mElem=null;  
        $this->mNext=null;  
    }
}