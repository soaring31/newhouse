<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-24
*/
namespace WeixinBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WeixinBundle extends Bundle
{
    public function getCompany()
    {
        return '08cms';
    }
}