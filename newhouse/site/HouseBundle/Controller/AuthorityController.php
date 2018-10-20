<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年07月06日
*/
namespace HouseBundle\Controller;

/**
* 管理角色
* @author house
*/
class AuthorityController extends Controller
{



    /**
    * 管理角色
    * house
    */
    public function authorityruleAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}