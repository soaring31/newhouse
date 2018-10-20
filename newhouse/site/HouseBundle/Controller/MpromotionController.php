<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年07月13日
 */

namespace HouseBundle\Controller;

/**
 * 活动
 * @author house
 */
class MpromotionController extends Controller
{
    /**
     * 活动管理
     * house
     */
    public function mpromotionmanageAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 报名
     * house
     */
    public function mpromotionordermanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}