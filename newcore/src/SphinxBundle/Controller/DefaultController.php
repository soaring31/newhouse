<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-03-28
*/
namespace SphinxBundle\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}
