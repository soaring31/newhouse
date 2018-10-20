<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月01日
*/
namespace ManageBundle\Controller;

/**
* 
* @author admina
*/
class AreaController extends Controller
{



    /**
    * 实现的getjson方法
    * admina
    */
    public function getjsonsAction()
    {
        $pid = $this->get('request')->get('pid',0);
    	
    	$info = $this->get('db.area')->findBy(array('pid' => $pid));
    	
    	return $this->showMessage('ok',1,$info);
    }

    /**
    * 实现的testaa方法
    * house
    */
    public function testaaAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}