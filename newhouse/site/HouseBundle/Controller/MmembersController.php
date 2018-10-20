<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月04日
*/
namespace HouseBundle\Controller;
        
/**
* 会员管理
* @author house
*/
class MmembersController extends Controller
{
        


    /**
    * 会员管理
    * house
    */
    public function mmembersmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

   

    

    /**
    * 会员关系
    * house
    */
    public function mnexusarcAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员角色列表
    * house
    */
    public function msetmemgroupAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 佣金提取审核
    * house
    */
    public function mcheckextractAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}