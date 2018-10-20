<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-05-18
*/
namespace HouseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HouseBundle extends Bundle
{
    public function getCompany()
    {
        return '08cms';
    }
}
