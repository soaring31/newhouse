<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年01月19日
*/
namespace HouseBundle\Controller;

/**
* 12345
* @author house
*/
class PulicController extends Controller
{

    /**
    * 实现的详情页方法
    */
    public function 详情页Action()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

}