<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月13日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class ListenController extends Controller
{
    /**
    * 实现的eventlisten方法
    * admina
    */
    public function eventlistenAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    public function controllerlistenAction()
    {
        $pageIndex = $this->get('request')->get('pageIndex', 1);
        $pageSize = $this->get('request')->get('pageSize', 8);
        $this->parameters['info'] = $this->get('db.event_controller')->findBy(array(),null, $pageSize, $pageIndex);
        return $this->render($this->getBundleName(), $this->parameters);
    }
}